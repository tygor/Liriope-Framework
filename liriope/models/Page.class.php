<?php

// Direct access protection
if( !defined( 'LIRIOPE' )) die( 'Direct access is not allowed.' );

//
// Page.class.php
//

class Page extends obj {

  // the file to use for render
  // TODO: this is confusing since the page parent is View... perhaps rename this variable to template?
  var $_view;

  var $vars = array();

  function __construct( $file=NULL ) {
    $this->_view = $file;
    $this->title = c::get( 'page.title' );
    $this->description = c::get( 'page.description' );
    $this->author = c::get( 'page.author' );
    $this->theme = c::get( 'theme' );
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
    $file = trim( $file, '/' );
    if( substr( $file, 0, 4 ) != 'http' ) $file = '/' . $file;
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

  private function transferStored() {
    // loop through the $this->get() variables and overload them
    foreach( $this->get() as $k => $v ) {
      $this->$k = $v;
    }
  }

  public function uri() {
    return $this->uri;
  }

  public function url( $route=FALSE ) {
    if( $this->isHomePage()) return url();
    if( $route && $this->controller && $this->action ) return $this->controller . '/' . $this->action;
    return url($this->uri);
  }

  public function root() {
    // return the root URI item
    $parts = explode( '/', trim( $this->uri, '/' ));
    return $parts[0];
  }

  public function isHomePage() {
    return ( $this->uri === c::get( 'home' )) ? TRUE : FALSE;
  }

  public function isActive( $uri=NULL ) {
    if( $uri === NULL ) return false;
    if( $this->uri === $uri ) return true;
    return false;
  }

  //
  // render()
  // attempts to render the page
  //
  // @param  bool   $return if TRUE, return the buffer string, if FALSE print it
  // @param  string $alias  the variable name to use in the _view file for $this
  // @return mixed  Returns the output buffer string, or echos it
  public function render( $return=TRUE, $alias='page' ) {
    content::start();
    $this->transferStored();
    $$alias = $this;
    include( $this->_view );
    $render = content::end( $return );
    if( $return ) return $render;
    echo $render;
  }
}

?>
