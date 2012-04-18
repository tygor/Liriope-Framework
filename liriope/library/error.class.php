<?php

// Direct access protection
if( !defined( 'LIRIOPE' )) die( 'Direct access is not allowed.' );

//
// error.php
// collects and displays issues and errors
//

class error extends obj {
  static $errors = array();

  public static function report( $params=array() ) {
    if( empty( $params ) || !is_array( $params )) return;
    self::$errors[] = $params;
  }

  public static function dump() {
    return self::$errors;
  }
}

