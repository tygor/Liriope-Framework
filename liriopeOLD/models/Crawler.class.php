<?php

// Direct access protection
if( !defined( 'LIRIOPE' )) die( 'Direct access is not allowed.' );

class crawler {
  // this is the root of the site
  static $root;

  // stores the found urls
  static $urls = array();

  // stores the urls that have been crawled already
  static $visited = array();

  // stores the internal urls
  static $internal = array();

  // position of the visited pointer
  static $pos = 0;

  // stores the external urls (though I'm not sure how to use these yet)
  static $external = array();

  static function crawl() {
    self::$root = c::get('url');
    $page = self::getPage('/');
    // index the home page
    // TODO: this grabs and indexes the whole HTML where in the MVC process of a pageview, it only grabs the page body content
    self::visit( 'home', $page );
    self::getHREF($page);
    self::traverse();
    return self::$visited;
  }

  static function traverse() {
    while( $i = self::nextplease() ) {
      $page = self::getPage( $i );
      self::visit( $i, $page );
      self::getHREF($page);
    }
  }

  static function nextplease() {
    // get current() of the internal and move pointer
    if( isset(self::$internal[self::$pos] )) {
      $current = self::$internal[self::$pos];
      if(isset($current) && !in_array( $current, self::$visited)) {
        self::$pos++;
        return $current;
      }
    }
    return false;
  }

  static function visit( $id, $html ) {
    if(!in_array($id, self::$visited)) {
      self::$visited[] = $id;
      index::store( $id, $html );
    }
  }

  static function getPage( $url ) {
    $url = self::$root . '/'.$url;
    $ch = curl_init();
    curl_setopt( $ch, CURLOPT_URL, $url );
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, TRUE);
    $output = curl_exec($ch);
    curl_close($ch);
    return $output;
  }

  static function getHREF( $s ) {
    $ignore = array( '/', '#' );
    $pattern = '#<a[^href]href=[\'"](.*?)[\'"]#i';
    preg_match_all( $pattern, $s, $matches );
    self::$urls = array_unique( array_merge(
      self::$urls,
      array_map(
        function ($v) {
          return str_replace( c::get('url') . '/', '', $v);
        },
        array_diff($matches[1], $ignore)
      )
    ));
    self::splitURLS( self::$urls );
  }

  // splitURLS()
  // stores the internal and external urls seperately
  //
  // @param   array  $urls The list of all urls captured for this page
  // @return  array  the list of internal urls
  static function splitURLS( $urls ) {
    $internal = array();
    $external = array();
    foreach( $urls as $u ) {
      // does it start with http(s)?
      if( substr( trim( $u ), 0, 5) == 'http:' ||
          substr( trim( $u ), 0, 6) == 'https:' ||
          substr( trim( $u ), 0, 4) == 'ftp:' ||
          substr( trim( $u ), 0, 5) == 'ftps:' ) {
        $external[] = $u;
      } else {
        $internal[] = $u;
      }
    }
    self::$internal = array_unique( array_merge( self::$internal, $internal ));
    self::$external = array_unique( array_merge( self::$external, $external ));
  }

}

?>
