<?php

namespace Liriope\Component\Search;

use Liriope\c;
use Liriope\Toolbox\a;
use Liriope\Toolbox\String;
use Liriope\Toolbox\File;
use Liriope\Toolbox\Router;
use Liriope\Toolbox\Directory;

class search {

  // timer
  var $start;
  var $stop;
  var $duration;

  // some search options
  var $casesensitive;
  var $wholeword;

  // the uris to ignore
  var $ignore = array();

  // the form's search field (the variable to pull from the Request)
  // DEPRECATED
  var $searchfield;

  // the array of words to search for
  var $searchwords;

  // the search query
  var $query;

  // the found results
  var $results = array();

  function __construct( $options=array(), $query = FALSE ) {
    // set the options
    $this->ignore =        a::get( $options, 'ignore', c::get('search.ignore'));
    $this->query =         a::get( $options, 'query', FALSE );
    // searchfield is DEPRECATED
    $this->searchfield =   a::get( $options, 'searchfield', FALSE );
    $this->casesensitive = a::get( $options, 'casesensitive', FALSE );
    $this->wholeword =     a::get( $options, 'wholeword', FALSE );
    
    // clean the user input
    //if( $this->searchfield) $this->query = trim( urldecode( Request::get( $this->searchfield )));
    $this->query = trim( urldecode( $query ));

    // escape for an empty query
    if( empty( $this->query )) return FALSE;

    // convert search to comma delimited string
    $searchwords = new String(preg_replace( Index::$stripPattern, ',', $this->query ));
    if( !$this->casesensitive ) $searchwords->to_lowercase();
    $this->searchwords = $searchwords->split(',');

    // remove ignored words
    $this->searchwords = array_diff( $this->searchwords, Index::$ignore );

    // check for an empty string again
    if( empty( $this->searchwords )) {
      trigger_error("The search words were all ignored, or the query was empty");
      return FALSE;
    }

  }

  // 
  // searchPages()
  // Does the search for content within the indexed pages
  //
  public function searchPages() {
    // start the timer
    $this->start = microtime(TRUE);

    // get the set of pages to search within--our indexed pages
    $pages = $this->getIndexedPages();

    // do the searching
    $this->results = $this->search( $pages );

    // account for the possibility of an empty result
    if( empty( $this->results )) {
      trigger_error("The search results were empty for the query '" . $this->query . "'", E_USER_WARNING);
      return FALSE;
    }

    // TODO: The excerpt seems to only grab the final searchword to pull an excerpt from and not the full set of searchwords. Also, it doesn't account for fuzzySearch.
    // excerpt the results
    // $this->excerpt( $this->results );

    // stop the timer
    $this->stop = microtime(TRUE);

    return $this;
  }

  // 
  // search()
  // searches the passed set of pages for the $this->searchwords
  //
  // @param array $pages The associative array of pages to look within
  // @param mixed $query The thing to look for
  //
  function search( $pages ) {
    // init an empty result array
    $result = array();
    // loop through the set of pages to search within
    foreach( $pages as $id => $page ) {

      // ignore certain pages
      if( in_array( $id, $this->ignore )) { continue; }

      // init an empty found array
      $found = array();

      // SEARCH
      if( $this->wholeword ) {
        // only search for the complete query string rather than the individual parts
        $found = $this->wholeWordSearch($page);
        // loop through the result and tally the score of word matches
        foreach($found as $f) {
          $score = isset($result[$id]['score']) ? $result[$id]['score'] : 0;
          $score += $f['score'];
          $result[$id] = array( 'title' => $page['title'], 'score' => $score );
        }
        // sort the result
        $this->sort( $result );
      }
      else {
        // The resulting array contains every word that is within the Levenshtein threshold
        // and it's corresponding score.
        $found = $this->fuzzySearch($page['index'], 0);
        if(count($found)) {
          // Scoring the results by word count alone would give skewed results for pages with less words to match.
          // If there is an exact match, it will be on the top of the list probably with a negative score.
          $score = 0;
          foreach($found as $f) {
            $score += $f['score'];
          }
          $result[$id] = array( 'title' => $page['title'], 'score' => $score);
          // sort the result
        }
        $this->sort( $result, 'ASC' );
      }
    }

    return $result;
  }

  // 
  // autocomplete()
  // 
  // TODO: use the Index model to store an autocomplete text file
  //       from which the results will come, and use that here rather
  //       than compiling the list of words every keypress.
  // 
  // TODO: when the above is satisfied, searh for more than a single word.
  // 
  public function autocomplete($limit=10, $threshold=3) {
    // init the results array
    $results = array();
    // get the indexed pages to extract the words to guess from
    $pages = self::getIndexedPages();
    // init the words array
    $words = array();
    // loop the indexed pages and extract words
    foreach($pages as $p) {
      if(is_array($p)) {
          if(array_key_exists('index', $p)) {
              $words += $p['index'];
          }
      }
    }
    // get the unique words only
    $words = array_flip(array_unique(array_keys($words)));
    // calculate the edit distance from the search query
    $results = $this->fuzzySearch($words, 3, false);
    // return 
    return array_slice($results, 0, $limit);
  }

  // 
  // wholeWordSearch()
  // Searches the passed set by whole word matching
  // 
  // @param  array  The set to search within
  // @return array  The matches
  // 
  private function wholeWordSearch($item) {
    // init empty result to return
    $result = array();
    // use an array_intersect to determine if the words to search for match any words
    // in the set's index of words
    $match = array_intersect($this->searchwords, array_keys($item['index']));
    // loop through the matched words and tally their count from the index
    if($match) {
      foreach($match as $m) {
        $result[$m] = array('score'=>$item['index'][$m]);
      }
    }
    return $result;
  }

  // 
  // fuzzySearch()
  // use the Levenshtein funciton to find a result set based on edit distance
  // loop through the class $searchwords and bulid a result per word against the $set
  // 
  // 1) use edit distance to find relevant matches
  // 2) decrease edit distance for clustered substrings
  // 
  // @param  array The set of items to search within where the key is the term and the value is the multiplier
  // @param  int   The levenshtein distance for a match <http://en.wikipedia.org/wiki/Levenshtein_distance>
  // 
  private function fuzzySearch($searchSet, $threshold=0, $useMultiplier=true) {
    // get the search query...
    $query = $this->searchwords;

    // init an empty results array
    $results = array();

    // loop through each searchword and compare it to the set
    foreach($query as $q) {
      // clean up spaces for the levenshtein function
      $q = strtolower(str_replace(' ', '', $q));

      foreach((array)$searchSet as $k => $v) {
        // limit the levenshtein parts to 255 characters
        $k = strtolower(substr($k, 0, 255));
        // calculate the edit distance
        $lev = levenshtein($q, $k);
        // extract a set of the unique characters from the query
        // mode 3: returns the unique characters
        $qChars = count_chars($q, 3);
        // check for clustered characters
        // subtract the strlen of the cluster from the lev score
        $pattern = '/['.preg_quote($qChars, '/').']*/i';
        preg_match_all($pattern, $k, $clusters);
        // count the longest cluster and subtract that from the lev score
        $discount = 0;
        foreach($clusters[0] as $cluster) {
          $n = strlen($cluster);
          $discount += $n;
        }
        // calculate the new lev score
        $lev = $lev - $discount;
        // add to the results if the distance is <= $threshold
        if($lev <= $threshold) {
          // multiple the lev score by the count of words in the searchSet
          if(!$useMultiplier || !isset($v)) { $v = 1; }
          $results[$k] = array('score' => $lev * $v);
        }
      }
    }

    // sort the results by the ['lev'] value
    uasort($results, function($a, $b) {
      $an = $a['score']; $bn = $b['score'];
      if($an==$bn) return 0;
      return ($an < $bn) ? -1 : 1;
    });

    return $results;
  }  

  // sort()
  // sorts the result pages
  //
  function sort( &$pages, $mode='DESC' ) {
    // TODO: enable sorting by other fields ex. date, alphabetically, etc.
    // only sort if the array conatins more than 1 item
    if( count($pages) <= 1 ) return; 
    if($mode === 'DESC') uasort( $pages, array( __CLASS__, 'sortResults' ));
    else uasort( $pages, array( __CLASS__, 'sortResultsRev' ));
  }

  private function sortResults( $a, $b ) {
    $aa = $a['score'];
    $bb = $b['score'];
    if( $aa == $bb ) return 0;
    return ( $aa > $bb ) ? -1 : 1;
  }

  private function sortResultsRev( $a, $b ) {
    $aa = $a['score'];
    $bb = $b['score'];
    if( $aa == $bb ) return 0;
    return ( $aa < $bb ) ? -1 : 1;
  }

  // exceprt()
  // trys to grab content around the found words as an excerpt
  //
  function excerpt( &$pages ) {
    if(empty($pages)) {
      trigger_error("The search page result array is empty", E_USER_WARNING);
      return FALSE;
    }
    foreach( $pages as $id => $page ) {
      // ignore certain pages
      if( in_array( $id, $this->ignore )) {
        // trigger_error('Ignoring this page: ' . $id, E_USER_WARNING);
        continue;
      }
      $idParts = explode('/', $id);
      extract( Router::getDispatch( (array) $idParts ));
      $controller = Router::callController( $controller, $action, $params );
      $pages[$id]['content'] = $controller->getPage()->get('content');

      $stripped = strip_tags( $pages[$id]['content'] );
      $words = '(.{0,50})(';
      foreach( $this->searchwords as $k => $word ) {
        if( $word !== a::first( $this->searchwords )) $words .= '|';
        $words .= preg_quote( $word );
      }
      $words .= ')(.{0,50})';
      $pattern = '/'.$words.'/im';
      preg_match_all( $pattern, $stripped, $matches );
      if( !$matches[1] ) {
        $pages[$id]['excerpt'] = '';
        continue;
      }
      $excerpt = '&hellip;';
      for( $i=0; $i < 3; $i++ ) {
        if( isset( $matches[1][$i] ) && isset( $matches[2][$i] ) && isset( $matches[3][$i] )) {
          $excerpt .= $matches[1][$i];
          $excerpt .= '<ins>' . $matches[2][$i] . '</ins>';
          $excerpt .= $matches[3][$i] . '&hellip;';
        } elseif( isset( $matches[0][$i] )) {
          $excerpt .= $matches[0][$i] . '&hellip;';
        }
      }
      $pages[$id]['excerpt'] = $excerpt;
    }
  }

  function duration() {
    if( empty( $this->stop )) return 0;
    if( empty( $this->duration )) $this->duration = $this->stop - $this->start;
    return round( $this->duration, 4);
  }

  function found() {
    return count( $this->results );
  }

  function results() {
    return $this->results;
  }

  function query() {
    return $this->query;
  }

  function words() {
    return a::glue( $this->searchwords, ' ' );
  }

  function getIndexedPages() {
    $dir = c::get( 'root.index', c::get( 'root.web' ) . '/index' );
    $files = Directory::read( $dir );
    $pages = array();
    foreach( $files as $f ) {
      $page = File::read( $dir . '/' . $f, 'json' );
      $pages[key($page)] = current( $page );
    }
    return $pages;
  }

}
