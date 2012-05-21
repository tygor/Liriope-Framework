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
  static $uri;
  static $isHome = false;
  static $path;
  static $file;
  static $extension;
  static $query;
  static $url;
  static $request_time;

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
    self::$request_time = server::get( 'REQUEST_TIME' );
    self::$route = $route;
    $uriArray = self::getURIArray();

    // http://site.com/controller/action/param/param/file.ext
    //                 |->             route              <-|
    //          host   |->         path          <-| file ext
    self::$file = a::last( $uriArray );
    self::$extension = Files::extension( self::$file );
    self::$path = implode( '/', $uriArray );
    self::$url = $route;
    self::$query = server::get( 'QUERY_STRING' );
  }

  static function isHome() {
    if( empty( self::$route )) {
      self::getURI();
    }
    return self::$isHome;
  }

  static function get() {
    return self::getURI();
  }

  static function getRawURI() {
    if( empty( self::$uri )) self::$uri = server::get( 'REQUEST_URI' );
    return self::$uri;
  }

  static function getURI() {
    if( empty( self::$route )) {
      $route = self::getRawURI();
      $route = trim( $route, '/' );
      if( $route === "" ) {
        self::$isHome = TRUE;
        $route = c::get( 'home', 'home' );
      }
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
