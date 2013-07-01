<?php

namespace Liriope\Toolbox;

use Liriope\Component;
use Liriope\c;

//
// uri Model
// --------------------------------------------------
// An object representation of the uri. This model is intended
// to describe itself and that's it. No logic, no alteration.
//
// only one instance should be available at a time
// all method are static
//

class Uri {
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
    self::$request_time = Server::get( 'REQUEST_TIME' );
    self::$route = $route;
    $uriArray = self::getArray();

    // http://site.com/controller/action/param/param/file.ext
    //                 |->             route              <-|
    //          host   |->         path          <-| file ext
    self::$file = A::last( $uriArray );
    self::$extension = File::extension( self::$file );
    self::$path = implode( '/', $uriArray );
    self::$url = $route;
    self::$query = Server::get( 'QUERY_STRING' );
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

  static function getDomain($withHTTP=FALSE) {
    $http = 'http://';
    $domain = Server::get('HTTP_HOST');
    return $withHTTP ? $http.$domain : $domain; 
  }

  static function getRawURI() {
    if( empty( self::$uri )) self::$uri = Server::get( 'REQUEST_URI' );
    return self::$uri;
  }

  static function getURI() {
    if( empty( self::$route )) {
      $route = new String(parse_url( self::getRawURI(), PHP_URL_PATH));
      $result = $route->minus('index.php')->trim("/")->get();
      if( $result === "" ) {
        self::$isHome = TRUE;
        $result = c::get( 'home', 'home' );
      }
      self::store( $result );
    }
    return self::$route;
  }

  static function getArray( $cleanRewrite=TRUE) {
    $route = parse_url( self::getURI(), PHP_URL_PATH );
    $route = explode( '/', $route);

    if( $cleanRewrite && strtolower( A::last( $route )) == 'index.php' ) {
      array_pop( $route );
    }

    return (array) $route;
  }

  // md5URI()
  // This is the un-rewritten URI, passed through the url() function, then the md5() function
  //
  static function md5URI() {
    return md5( \url( self::$path ));
  }

  // toRelative()
  // takes the passed file system path and converts it to the web root relative path
  //
  // @param  string  $path The path to convert
  // @return string  Returns the new path relative to the web root
  static function toRelative($path) {
    $return = new String($path);
    return $return->minus(\c::get('root.web', 'web'))->get();
  }

}
