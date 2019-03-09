<?php

namespace Liriope\Models;

use Liriope\c;
use Liriope\Component\Content\Buffer;
use Liriope\Toolbox\Uri;

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

  static function getFolder() {
    $folder = c::get('root.web') . '/';
    $folder .= self::$folder ?: '/';
    return Uri::toRelative($folder);
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
    @extract( self::$vars );
    // REQUIRED: pass 'page' and 'content' to the theme
    @extract( $vars );
    // the $liriope variable is used in each theme as the dynamic content holder
    $liriope = $content;
    Buffer::start();
    require_once( $file );
    $output = Buffer::end( $return );
    return $output;
  }
}

?>
