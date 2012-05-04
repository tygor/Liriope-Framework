<?php

// direct access proctection
if(!defined('LIRIOPE')) die( 'Direct access is not allowed');

//
// theme.php
// Handles the display of the theme
//

class theme {
  static public $folder;

  static public $vars = array();

  static function set( $name, $val=FALSE ) {
    if( is_array( $name )) {
      self::$vars = array_merge( self::$vars, $name );
    } else {
      self::$vars[$name] = $val;
    }
  }

  static function get( $name=NULL, $default=NULL ) {
    if( $name===NULL ) return (array) self::$vars;
    return a::get( self::$vars, $name, $default );
  }

  static function load( $theme=NULL, $vars=array(), $return=FALSE ) {
    if( $theme===NULL ) $theme = c::get( 'default.theme' );
    $folder = c::get( 'root.theme' ) . '/' . $theme;
    self::$folder = $folder;
    $file = $folder . '/' . 'index.php';
    return self::loadFile( $file, $vars, $return );
  }

  static function loadFile( $file, $vars=array(), $return=FALSE ) {
    if( !file_exists( $file )) return FALSE;
    @extract( self::$vars );
    @extract( $vars );
    content::start();
    require( $file );
    return content::end( $return );
  }
}

?>
