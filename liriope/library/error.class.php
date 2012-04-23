<?php

// Direct access protection
if( !defined( 'LIRIOPE' )) die( 'Direct access is not allowed.' );

//
// error.php
// collects and displays issues and errors
//

class error {
  static $_ = array();

  public static function report( $params=array() ) {
    if( empty( $params ) || !is_array( $params )) return;
    self::$_[] = $params;
  }

  public static function dump() {
    return self::$_;
  }

  static function set( $id=NULL, $val=NULL ) {
    if( $id === NULL ) return false;
    self::$_[$id] = $val;
  }

  static function get( $id=NULL, $default=NULL ) {
    if( $id === NULL ) return self::$_;
    return self::$_[$id] ? self::$_[$id] : $default ;
  }

  static function render() {
    content::get( c::get( 'root.liriope' ) . '/views/errors/debugging.php', FALSE );
  }
}

