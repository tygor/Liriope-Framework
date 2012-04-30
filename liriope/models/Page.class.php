<?php

// Direct access protection
if( !defined( 'LIRIOPE' )) die( 'Direct access is not allowed.' );

//
// Page.class.php
//

class Page {

  static public $_content; 

  static public $vars    = array();
  static public $css     = array();
  static public $scripts = array();
  static public $scriptBlocks = array();

  public function __construct() {
    // add the default stylesheets and scripts
    View::addStylesheet( 'css/style.css' );
    View::addScript( 'js/script.js' );
  }

  function set( $key, $value=FALSE ) {
    if( is_array( $key )) {
      self::$vars = array_merge( self::$vars, $key );
    } else {
      self::$vars[$key] = $value;
    }
    return $value;
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

  public function render( $file=NULL, $content=array(), $return=TRUE ) {
    // if no $file is set, look for a stored value
    if( $file === NULL && isset( $this->file )) $file = $this->file;

    // doesn't use content::get() because then the page would be on the content context
    // rather than the Page context.
    content::start();
    if( is_array( $content )) extract( $content );
    $page =& $this;
    include( $file );
    $render = content::end( $return );
    if( $return ) return $render;
    echo $render;
    //return content::get( $file, $content, $return );
  }
}

