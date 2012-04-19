<?php

// Direct access protection
if( !defined( 'LIRIOPE' )) die( 'Direct access is not allowed.' );

class uri {
  static protected $route;
  static $path;
  static $file;
  static $filename;
  static $extension;
  static $query;
  static $url;

  static function param( $name=NULL ) {
    if( $name === NULL ) return false;
    if( self::${$name} ) return self::${$name};
  }

  static function store( $route ) {
    // stores the pieces of the uri
    self::$route = $route;
    $uriArray = self::getURIArray();

    // check for a file extension
    if( $pos = strpos( $last = a::last( $uriArray ), '.' )) {
      self::$file = $last;
      self::$extension = substr( $last, ++$pos );
      self::$filename = substr( $last, 0, --$pos );
      array_pop( $uriArray );
    }

    self::$path = implode( '/', $uriArray );
    self::$url = $route;
  }

  static function get() {
    return self::getURI();
  }

  static function getURI() {
    if( empty( self::$route )) {
      $route = server::get( 'REQUEST_URI' );
      $route = trim( $route, '/' );
      if( $route === "" ) $route = c::get( 'home', 'home' );
      self::store( $route );
    }
    return self::$route;
  }

  static function getURIArray( $cleanRewrite=TRUE) {
    $route = self::getURI();
    $route = explode( '/', $route);

    if( $cleanRewrite && strtolower( $route[0] ) == 'index.php' ) {
      array_shift( $route );
    }

    return (array) $route;
  }

} ?>
