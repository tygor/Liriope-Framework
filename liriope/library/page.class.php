<?php
/* --------------------------------------------------
 * page.class.php
 * --------------------------------------------------
 *
 */

// Direct access protection
if( !defined( 'LIRIOPE' )) die( 'Direct access is not allowed.' );

class page {

  static public $_content; 

  static public $vars    = array();
  static public $css     = array();
  static public $scripts = array();
  static public $scriptBlocks = array();

  static function start() {
    // set default values
    self::set( 'page.title', 'Liriope : Monkey Grass' );
    self::set( 'page.DOCTYPE', '<!DOCTYPE html>' );

    // initialize the content variable
    self::set( '_content', '' );

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
    return self::renderFile( $file, $vars, $dump );
  }

  static function renderFile( $file, $vars=array(), $dump=FALSE ) {
    if( !file_exists( $file )) return false;

    /* OMIT: and use the double colon (::) reference to page::get() instead
    @extract( self::$vars );
    @extract( $vars );
    */

    // the controller passes variables to the view
    // and the view passes them here to the page
    // so set these variables for output
    self::set( $vars );
    
    ob_start();

    require( $file );

    if( $dump ) {
      $content = ob_get_contents();
      ob_end_clean();
      return $content;
    }
    ob_end_flush();
  }
}

