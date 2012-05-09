<?php

// Direct access protection
if( !defined( 'LIRIOPE' )) die( 'Direct access is not allowed.' );

//
// Page.class.php
//

class Page extends obj {
  var $_view;
  var $_content; 
  var $vars = array();

  function __construct( $file=NULL ) {
    $this->_view = $file;
    $this->title = c::get( 'page.title' );
    $this->description = c::get( 'page.description' );
    $this->author = c::get( 'page.author' );
    $this->theme = c::get( 'theme', c::get( 'default.theme' ));
    $this->DOCTYPE = c::get( 'page.DOCTYPE' );
  }

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

  public function css( $file=NULL, $rel='stylesheet' ) {
    if( $file===NULL ) return false;
    $this->vars['css'][] = array( 'file' => $file, 'rel' => $rel );
    return $file;
  }

  public function js( $file=NULL, $type='text/javascript' ) {
    if( $file===NULL ) return false;
    $this->vars['js'][] = array( 'file' => $file, 'type' => $type );
  }

  public function script( $script=NULL ) {
    if( $script===NULL ) return false;
    $this->vars['script'][] = $script;
  }

  public function DEPaddContent( $html ) {
    $this->content .= $html;
    return $this;
  }

  public function DEPgetContent() {
    return $this->content;
  }

  private function transferStored() {
    // loop through the $this->get() variables and overload them
    foreach( $this->get() as $k => $v ) {
      $this->$k = $v;
    }
  }

  public function render( $return=TRUE ) {
    content::start();
    $this->transferStored();
    $page =& $this;
    include( $this->_view );
    $render = content::end( $return );
    $render = filter::doFilters( $render );
    if( $return ) return $render;
    echo $render;
  }
}

?>

