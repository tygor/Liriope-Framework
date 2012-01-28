<?php

// Direct access protection
if( !defined( 'LIRIOPE' )) die( 'Direct access is not allowed.' );

/*
 * --------------------------------------------------
 * Configuration class
 * --------------------------------------------------
 */

class c
{
  private static $config = array();

  static function get( $key=NULL, $default=NULL )
  {
    if( empty( $key )) return self::$config;
    return isset( self::$config[$key] ) ? self::$config[$key] : $default;
  }

  static function set( $key, $value=NULL )
  {
    if( is_array( $key ))
    {
      self::$config = array_merge( self::$config, $key );
    }
    else
    {
      self::$config[$key] = $value;
    }
  }
}

?>
