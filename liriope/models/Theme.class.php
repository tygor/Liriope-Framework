<?php

// direct access proctection
if(!defined('LIRIOPE')) die( 'Direct access is not allowed');

//
// Theme.php
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
    $folder = c::get( 'theme.folder', 'themes' ) . '/' . $theme;
    self::$folder = $folder;
    $realfolder = c::get( 'root.theme' ) . '/' . $theme;
    $file = $realfolder . '/' . 'index.php';
    return self::loadFile( $file, $vars, $return );
  }

  static function loadFile( $file, $vars=array(), $return=FALSE ) {
    if( !file_exists( $file )) return FALSE;
    global $site;
    global $page;
    @extract( self::$vars );
    // we feed the Theme with the rendered $page content from the View
    // so here, we simply need to assign it a standardized variable name
    // to be used in the theme files.
    $liriope = $vars;
    content::start();
    require( $file );
    return content::end( $return );
  }
}

?>
