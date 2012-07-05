<?php

// Direct access protection
if( !defined( 'LIRIOPE' )) die( 'Direct access is not allowed.' );

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

    $this->ignore =        a::get( $options, 'ignore', array( 'home' ) );
    $this->query =         a::get( $options, 'query', FALSE );
    $this->searchfield =   a::get( $options, 'searchfield', FALSE );
    $this->casesensitive = a::get( $options, 'casesensitive', FALSE );
    $this->wholeword =     a::get( $options, 'wholeword', FALSE );
    
    // clean it
    if( $this->searchfield) {
      $this->query = trim( urldecode( r::get( $this->searchfield )));
    }

    // escape for an empty string
    if( empty( $this->query )) return FALSE;

    // convert search to comma delimited string
    $this->searchwords = preg_replace( '/[^\pL]/u', ',', preg_quote( $this->query ));
    if( !$this->casesensitive ) $this->searchwords = strtolower( $this->searchwords );
    $this->searchwords = str::split( $this->searchwords, ',' );

    // escape for an empty string
    if( empty( $this->searchwords )) return FALSE;

    $pages = $this->getIndexedPages();
    $result = array();
    
    foreach( $pages as $id => $page ) {
      if( in_array( $id, $this->ignore )) continue;
      $found = array();

      // whole word matching
      if( $this->wholeword ) {
        $m = array_intersect( $this->searchwords, array_keys( $page ));

        // loop the matched words, grab thier count from the index, and tally a score
        foreach( $m as $v ) {
          $found[$v] = $page[$v];
        }
      } else  {
        foreach( $this->searchwords as $s ) {
          $m = a::searchKeys( $page, $s );
          $found[key($m)] = current( $m );
        }
      }

      $count = array_sum($found);
      if( $count ) {
        $result[$id] = $count;
      }
    }

    if( empty( $found )) return FALSE;
    arsort( $result );
    $this->results = $result;
    
    // stop the timer
    $this->stop = microtime(TRUE);
  }

  function duration() {
    if( empty( $this->duration )) $this->duration = $this->stop - $this->start;
    return $this->duration;
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

class index {

  // TODO: have the 404 page tell index which pages can't be found as a way to clean up old indexes.
  // TODO: figure out how to get each page title and excerpt to show during the results
  // TODO: does the excerpt addition go here, or in the searching? If in searching, I can highlight the searched terms

  // an array of urls to ignore
  static $ignoreURLs = array( 'search' );

  // an array of all found URLs
  static $urls = array();
  // an array of saved URLs to crawl for indexing
  static $internal = array();
  // a list of external links
  static $external = array();

  // the variable to hold our cleaned content strings in an array
  static $content = array();

  // the word count array
  static $tally = array();

  // the named meta tags to keep
  static $metaNames = array( 'description', 'author', 'keywords' );
  // the words to ignore
  static $ignore = array( 'i', 'a', 'an', 'and', 'the', 'some', 'to', 'for', 'that', 'it', 'is', 'of', 'so', 's' );

  // store()
  //
  // @param  object  $html The page content to index
  //
  static function store( $id, $html ) {
    if( a::contains( self::$ignoreURLs, $id )) return false;

    // let's remove stuff that we don't want, and grab stuff we do
    self::saveLinks( $html );
    self::organizeLinks( self::$urls[1], c::get( 'url' ) );
    self::$content = a::combine( self::$content, self::findImageText( $html ));
    self::$content = a::combine( self::$content, self::findMeta( $html ));
    $html = self::removeInline( $html );
    $html = self::removeComments( $html );
    $words = explode( ',', trim( str::stripToWords( strip_tags( $html )), ' ,' ));
    self::$content = a::combine( self::$content, $words );

    // now tally what we got
    self::countWords();

    $store = array( $id => self::$tally );
    $dir = c::get( 'root.index', c::get( 'root.web' ) . '/index' );
    $file = $dir . '/' . self::prep( $id ) . '.txt';

    f::write( $file, $store );
  }

  // removeInline()
  // strips out <head>, <script> and <style> tags inlcuding their contents
  //
  static function removeInline( $content ) {
    $content = preg_replace( '/<script.*>.*<\/script>/imsxU', '', $content );
    $content = preg_replace( '/<style.*>.*<\/style>/imsxU', '', $content );
    $content = preg_replace( '/<head.*>.*<\/head>/imsxU', '', $content );
    return $content;
  }

  // removeComments()
  // strips out HTML comments
  static function removeComments( $content ) {
    $content = preg_replace( '/<!--.*-->/imsxU', '', $content );
    return $content;
  }

  // saveLinks()
  // save the anchor tags' href locations for crawling
  //
  static function saveLinks( $content ) {
    $pattern = "/<a.*href=['\"](.*?)['\"].*>.*<\/a>/i";
    preg_match_all( $pattern, $content, self::$urls );
  }

  // organizeLinks()
  // traverse the found links and extract the internal links
  //
  static function organizeLinks( $array, $origin='' ) {
    $result = array();
    $c = count( $array );
    for( $i=0; $i<$c; $i++ ) {
      if( !empty( $array[$i] )) {
        if( strpos( $array[$i], '://', 0 ) === FALSE ||
            substr( $array[$i], 0, strlen( $origin )) === $origin ) {
          if( $array[$i] !== '#' ) {
            $result[] = $array[$i];
          }
        }
      }
    }
    self::$internal = $result;
    self::$external = array_diff( self::$urls[1], $result );
  }

  // findImageText()
  // seeks and returns content from title and alt attributes
  //
  static function findImageText( $content ) {
    $title = array();
    $pattern = "/title=['\"]([^'\"]*)['\"]*+/i";
    preg_match_all( $pattern, $content, $title );

    $alt = array();
    $pattern = "/alt=['\"]([^'\"]*)['\"]*+/i";
    preg_match_all( $pattern, $content, $alt );

    $titlewords = implode( ' ', $title[1] );
    $altwords = implode( ' ', $alt[1] );
    return explode( ' ', trim( $titlewords . ' ' . $altwords ));
  }

  // findMeta()
  // seeks and returns the contents of named meta tags from the head
  //
  static function findMeta( $content ) {
    $found = array();
    $pattern = '/<meta.*name=[\'"]([a-z0-9 ]*)[\'"].*>/i';
    preg_match_all( $pattern, $content, $found );
    $return = array();
    for( $c=0; $c<count($found[0]); $c++ ) {
      if( in_array( $found[1][$c], self::$metaNames )) {
        $pattern = '/content=[\'"]([a-z0-9\. ,=-]*)[\'"]/i';
        preg_match_all( $pattern, $found[0][$c], $content );
        $return = a::combine( $return, explode( ' ', $content[1][0] ));
      }
    }
    return $return;
  }

  static function countWords( $array=NULL ) {
    // TODO: Look into the PHP function array_count_values()
    if( $array === NULL ) $array = self::$content;
    $tally = array();
    foreach( $array as $word ) {
      $word = trim( strtolower( $word ), ' .,-"\'');
      if( !isset( $tally[$word] )) $tally[$word] = 1;
      else $tally[$word] = $tally[$word] + 1;
    }
    $tally = array_diff_key( $tally, array_flip( static::$ignore ));
    arsort( $tally );
    self::$tally = $tally;
  }

  // prep()
  // prepares the string for writing a file
  //
  static function prep( $string ) {
    return str_replace( '/', '|', $string );
  }

} ?>
