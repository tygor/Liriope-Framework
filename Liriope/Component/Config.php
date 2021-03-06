<?php

namespace Liriope\Component;

//
// --------------------------------------------------
// Configuration class
// --------------------------------------------------
//

class Config {
  private static $config = array();

  //
  // get
  //
  // returns the variable value or the complete array of config variables
  // if $key is empty
  // 
  // string $key     the variable name to retrieve
  // string $default the default value if that variable is not set
  static function get( $key=NULL, $default=NULL )
  {
    // return everything if nothing specific is asked for
    if( empty( $key )) return self::$config;
    // is that key set?
    if( isset( self::$config[$key] )) {
      return self::$config[$key];
    }
    // return the default if there is one
    if( $default !== NULL ) {
      return $default;
    }
    // finally, return FALSE since we got nothin'
    return FALSE;
  }

  //
  // set
  // 
  // string $key
  // string $value
  // bool   $overwrite
  static function set( $key, $value=NULL, $overwrite=true ) {
    // if param 1 is not an array, then store the variable and we're done
    if( !is_array( $key )) {
      // if overwrite is on, just do it
      // OR if overwrite is off, but the variable isn't set
      if( $overwrite || !isset( self::$config[$key] )) {
        self::$config[$key] = $value;
        return true;
      }
      return false;
    }
    // if it IS an array, then set all of them
    foreach( $key as $val ) {
      list( $k, $v, $o ) = $val + array( NULL, NULL, NULL );
      self::set( $k, $v, $o );
    }
    return true;
  }
}

// alias the Config class into multiple namespaces as "c"
class_alias('Liriope\Component\Config',                   'c');
class_alias('Liriope\Component\Config',           'Liriope\c');
class_alias('Liriope\Component\Config', 'Liriope\Component\c');
