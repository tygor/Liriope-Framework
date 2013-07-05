<?php

namespace Liriope\Component\Search;

use Liriope\Toolbox\a;
use Liriope\Toolbox\String;
use Liriope\Toolbox\File;

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

  // the form's search field
  var $searchfield;

  // the array of words to search for
  var $searchwords;

  // the search query
  var $query;

  // the found results
  var $results = array();

  function __construct( $options=array() ) {
    // start the timer
    $this->start = microtime(TRUE);

    // set the options
    $this->ignore =        a::get( $options, 'ignore', array( 'home', 'search' ) );
    $this->query =         a::get( $options, 'query', FALSE );
    $this->searchfield =   a::get( $options, 'searchfield', FALSE );
    $this->casesensitive = a::get( $options, 'casesensitive', FALSE );
    $this->wholeword =     a::get( $options, 'wholeword', FALSE );
    
    // clean it
    if( $this->searchfield) $this->query = trim( urldecode( r::get( $this->searchfield )));

    // escape for an empty string
    if( empty( $this->query )) return FALSE;

    // convert search to comma delimited string
    //$this->searchwords = preg_replace( index::$stripPattern, ',', $this->query );
    $searchwords = new String(preg_replace( index::$stripPattern, ',', $this->query ));
    if( !$this->casesensitive ) $searchwords->to_lowercase();
    $this->searchwords = $searchwords->split(',');

    // remove ignored words
    $this->searchwords = array_diff( $this->searchwords, index::$ignore );

    // escape for an empty string
    if( empty( $this->searchwords )) {
      trigger_error("The search words were all ignored, or the query was empty");
      return FALSE;
    }

    // get the set of pages to search within--our indexed pages
    $pages = $this->getIndexedPages();

    $this->results = $this->search( $pages );
    if( empty( $this->results )) {
      trigger_error("The search results were empty for the query '" . $this->query . "'", E_USER_WARNING);
      return FALSE;
    }

    $this->sort( $this->results );
    $this->excerpt( $this->results );
    
    // stop the timer
    $this->stop = microtime(TRUE);
  }

  function search( $pages ) {
    $result = array();
    foreach( $pages as $id => $page ) {
      // ignore certain pages
      if( in_array( $id, $this->ignore )) {
        trigger_error('Ignoring this page: ' . $id, E_USER_WARNING);
        continue;
      }
      $found = array();

      // whole word matching
      if( $this->wholeword ) {
        $m = array_intersect( $this->searchwords, array_keys( $page['index'] ));

        // loop the matched words, grab thier count from the index, and tally a score
        if( $m ) {
          foreach( $m as $v ) {
            $found[$v] = $page['index'][$v];
          }
        }
      } else  {
        foreach( $this->searchwords as $k => $s ) {
          $m = a::searchKeys( $page['index'], $s );
          if( !empty( $m )) { $found += $m; }
        }
      }

      $count = array_sum($found);
      if( $count ) {
        $result[$id] = array( 'title' => $page['title'], 'count' => $count );
      }
    }
    return $result;
  }

  // sort()
  // sorts the result pages
  //
  function sort( &$pages, $mode='DESC' ) {
    // TODO: enable sorting by other fields ex. date, alphabetically, etc.
    // only sort if the array conatins more than 1 item
    if( count($pages) <= 1 ) return; 
    uasort( $pages, array( __CLASS__, 'sortResults' ));
  }

  private function sortResults( $a, $b ) {
    $aa = $a['count'];
    $bb = $b['count'];
    if( $aa == $bb ) return 0;
    return ( $aa > $bb ) ? -1 : 1;
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
      $id = explode($id, '/');
      extract( router::getDispatch( (array) $id ));
      $controller = router::callController( $controller, $action, $params );
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
    $files = dir::read( $dir );
    $pages = array();
    foreach( $files as $f ) {
      $page = f::read( $dir . '/' . $f, 'json' );
      $pages[key($page)] = current( $page );
    }
    return $pages;
  }

}
