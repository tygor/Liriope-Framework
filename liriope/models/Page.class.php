<?php

// Direct access protection
if( !defined( 'LIRIOPE' )) die( 'Direct access is not allowed.' );

//
// Page.class.php
//

class Page {
  static public $_content; 
  public $vars = array();

  public function set( $key, $value=FALSE ) {
    if( is_array( $key )) {
      self::$vars = array_merge( self::$vars, $key );
    } elseif( $value === NULL ) {
      unset( self::$vars[$key] );
    } else {
      $this->vars[$key] = $value;
    }
  }

  public function get( $key=NULL, $default=NULL ) {
    if( $key===NULL ) return (array) $this->vars;
    if( isset( $this->vars[$key] )) return $this->vars[$key];
    return $default;
  }

  public function addStylesheet( $file=NULL, $rel='stylesheet' ) {
    if( $file===NULL ) return false;
    $this->vars['stylesheets'][] = array( 'file' => $file, 'rel' => $rel );
    return $file;
  }

  static function addScript( $file=NULL, $type='text/javascript' ) {
    if( $file===NULL ) return false;
    self::$vars['scripts'][] = array( 'file' => $file, 'type' => $type );
  }

  static function addScriptBlock( $script=NULL ) {
    if( $script===NULL ) return false;
    self::$vars['scriptblocks'][] = $script;
  }

  static function addContent( $html ) {
    self::$_content .= $html;
  }

  public function getContent() {
    return self::$_content;
  }

  public function render( $file=NULL, $content=array(), $return=TRUE ) {
    content::start();
    if( is_array( $content )) extract( $content );
    $page =& $this;
    include( $file );
    $render = content::end( $return );
    if( $return ) return $render;
    echo $render;
  }
}

