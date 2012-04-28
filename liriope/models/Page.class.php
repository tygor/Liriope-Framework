<?php

// Direct access protection
if( !defined( 'LIRIOPE' )) die( 'Direct access is not allowed.' );

//
// Page.class.php
// all methods are STATIC
//

class Page {

  static public $_content; 

  static public $vars    = array();
  static public $css     = array();
  static public $scripts = array();
  static public $scriptBlocks = array();

  static function start() {
    // set default values
    // TODO: I would prefer that these are kept using the c (config) class and not duplicate set/get methods
    self::set( 'page.title', self::get( 'page.title', 'Liriope : Monkey Grass' ));
    self::set( 'page.DOCTYPE', self::get( 'page.DOCTYPE', '<!DOCTYPE html>' ));

    // add the default stylesheets and scripts
    self::addStylesheet( 'css/style.css' );
    self::addScript( 'js/script.js' );
  }

  static function set( $key, $value=FALSE ) {
    if( is_array( $key )) {
      self::$vars = array_merge( self::$vars, $key );
    } else {
      self::$vars[$key] = $value;
    }
  }

  static function get( $key=NULL, $default=NULL ) {
    if( $key===NULL ) return (array)self::$vars;
    if( !empty( self::$vars[$key] )) return self::$vars[$key];
    return $default;
  }

  static function addStylesheet( $file=NULL, $rel='stylesheet' ) {
    if( $file===NULL ) return false;
    self::$css[] = array( 'file' => $file, 'rel' => $rel );
  }

  static function getStylesheets() {
    return (array) self::$css;
  }

  static function addScript( $file=NULL, $type='text/javascript' ) {
    if( $file===NULL ) return false;
    self::$scripts[] = array( 'file' => $file, 'type' => $type );
  }

  static function getScripts() {
    return (array) self::$scripts;
  }

  static function addScriptBlock( $script=NULL ) {
    if( $script===NULL ) return false;
    self::$scriptBlocks[] = $script;
  }

  static function getScriptBlocks() {
    return (array) self::$scriptBlocks;
  }

  static function addContent( $html ) {
    self::$_content .= $html;
  }

  public function getContent() {
    return self::$_content;
  }

  static function render( $file, $vars = array(), $dump=FALSE ) {
    // receives the view file and variables
    // and outputs the result in a buffer or directly
    self::set( $vars );
    return self::renderFile( $file, $vars, $dump );
  }

  static function renderFile( $file, $vars=array(), $dump=FALSE ) {
    if( !file_exists( $file )) return false;
    return content::get( $file, NULL, $dump );
  }
}

