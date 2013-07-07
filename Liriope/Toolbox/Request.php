<?php

namespace Liriope\Toolbox;

class Request {

  // stores all sanatized request data
  static private $_ = FALSE;

  // get()
  // gets a value from the $_REQUEST by key
  //
  static function get( $key=FALSE, $default=NULL ) {
    if( self::method() == 'GET' ) $request = self::data();
    else $request = array_merge( self::data(), self::body() );
    if(!$key) return $request;
    return a::get( $request, $key, $default );
  }

  // method()
  // returns the request method
  //
  static function method() {
    return strtoupper( server::get( 'request_method' ));
  }

  // data()
  // grabs the data from the request and sanatizes it
  //
  static function data() {
    if( self::$_ ) return self::$_;
    return self::$_ = self::sanatize( $_REQUEST );
  }

  // sanatize()
  // cleans the data since it is user input
  //
  static function sanatize( $data ) {
    foreach( $data as $k => $v ) {
      if( !is_array( $v )) {
        $v = new String($v);
        $v = $v->stripslashes()->get();
      } else {
        $v = self::sanatize( $v );
      }
      $data[$k] = $v;
    }
    return $data;
  }

  // body()
  // returns the request body
  //
  static function body() {
    @parse_str( @file_get_contents( 'php://input' ), $body );
    return self::sanatize( (array) $body );
  }

}

