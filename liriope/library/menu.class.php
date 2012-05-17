<?php

// Direct access protection
if( !defined( 'LIRIOPE' )) die( 'Direct access is not allowed.' );

//
// menu
//

class menu extends obj {
  var $label;
  var $url;
  var $parent;
  var $isActive = FALSE;

  public function __construct( $label=NULL, $url=NULL ) {
    if( $label === NULL ) $label = ucfirst( c::get( 'home' ));
    if( $url === NULL ) $url = c::get( 'home' );
    $this->label = $label;
    $this->url = $url;
    $this->parent = FALSE;
    $this->uri = uri::get();
    return $this;
  }

  public function addChild( $label, $url ) {
    $menu = new menu( $label, $url );
    $menu->parent =& $this;
    $this->_[ $url ] = $menu;
    return $this;
  }

  public function hasChildren() {
    if( count( $this->_ ) >= 1 ) return true;
    return false;
  }

  public function getChildren() {
    return $this->_;
  }

  public function getParent() {
    if( $this->parent ) return $this->parent;
    return FALSE;
  }

  public function getRoot() {
    if( $this->parent && $this->parent->url !== c::get( 'home' )) return $this->parent->getParent();
    return $this;
  }

  public function find( $k ) {
    return a::get( $this->_, $k );
  }

  public function findDeep( $k ) {
    // check children
    $menu = $this->find( $k );
    if( $menu ) return $menu;
    // check the children's children
    foreach( $this->getChildren() as $child ) {
      $menu = $child->findDeep( $k );
      if( $menu ) return $menu;
    }
    return FALSE;
  }
}

?>
