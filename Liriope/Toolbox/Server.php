<?php
namespace Liriope;

namespace Liriope\Toolbox;

//
// Server class
//
// @package Liriope
//

class Server {

  //
  // get
  // returns a value from the _SERVER array
  //
  // @param  mixed  $key The key to grab from the _SERVER array
  // @param  mixed  $default The value to return if nothing is found
  // @return mixed  will return the value for the key or default
  //
  static function get( $key=FALSE, $default=NULL ) {
    if( $key===FALSE ) return $_SERVER;
    return a::get( $_SERVER, strtoupper( $key ), $default );
  }

}
