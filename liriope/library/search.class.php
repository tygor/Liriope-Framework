<?php

// Direct access protection
if( !defined( 'LIRIOPE' )) die( 'Direct access is not allowed.' );

class search {
}

class index {


  // an array of all found URLs
  static $urls = array();
  // an array of saved URLs to crawl for indexing
  static $internal = array();
  // a list of external links
  static $external = array();

  // the variable to hold our cleaned content strings in an array
  static $content = array();

  // the named meta tags to keep
  static $metaNames = array( 'description', 'author', 'keywords' );

  // store()
  //
  // @param  object  $html The page content to index
  //
  static function store( $id, $html ) {
    // let's remove stuff that we don't want, and grab stuff we do
    self::saveLinks( $html );
    self::organizeLinks( self::$urls[1], c::get( 'url' ) );
    $html = self::removeInline( $html );
    $html = self::removeComments( $html );
    self::$content = a::combine( self::$content, self::findImageText( $html ));
    self::$content = a::combine( self::$content, self::findMeta( $html ));
print_r(self::$content);
die();
  }

  // removeInline()
  // strips out <script> and <style> tags inlcuding their contents
  //
  static function removeInline( $content ) {
    $content = preg_replace( '/<script.*>.*<\/script>/imsxU', '', $content );
    $content = preg_replace( '/<style.*>.*<\/style>/imsxU', '', $content );
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
    return a::combine( $title[1], $alt[1] );
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
        $return = a::combine( $return, $content[1] );
      }
    }
    return $return;
  }

} ?>
