<?php

//
// uri Model
// --------------------------------------------------
// An object representation of the uri. This model is intended
// to describe itself and that's it. No logic, no alteration.
//
// only one instance should be available at a time
// all method are static
//

// Direct access protection
if( !defined( 'LIRIOPE' )) die( 'Direct access is not allowed.' );

class uri {
  static protected $route;
  static $path;
  static $file;
  static $query;
  static $url;

  //
  // param()
  // determines if the passed variables exists and are not empty
  //
  static function param() {
    $params = func_get_args();
    if( func_num_args() == 1 && $r = self::${func_get_arg(0)} )  return $r;
    foreach( $params as $v ) {
      if( !self::${$v} ) return FALSE;
    }
    return TRUE;
  }

  //
  // store()
  // stores the tidbits of the uri into semantic variables
  //
  static function store( $route ) {
    self::$route = $route;
    $uriArray = self::getURIArray();

    // check for a file extension
    if( $pos = strpos( $last = a::last( $uriArray ), '.' )) {
      self::$file = new Files( $route );
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
