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
  var $active = FALSE;

  function __construct( $label=NULL, $url=NULL ) {
    $this->label = $label;
    $this->url = $url;
    return $this;
  }

  function setActive() {
    if( !$this->isActive() ) $this->isActive( TRUE );
  }

  function isActive( $inherit=FALSE ) {
    if( $this->active ) return $this->active;
    if( $inherit || $this->url == uri::get() ) {
      if( $this->hasParent() ) $this->getParent()->isActive( TRUE );
      $this->active = TRUE;
      return TRUE;
    }
    return FALSE;
  }

  function findActive() {
    foreach( $this->getChildren() as $child ) {
      if( $child->isActive()) return $child;
    }
    return FALSE;
  }

  function addChild( $label, $url ) {
    $menu = new menu( $label, $url );
    $menu->parent = $this;
    $menu->isActive();
    $this->$url = $menu;
    return $this;
  }

  function hasChildren() { return ( count( $this->_ ) > 0 ) ? TRUE : FALSE; }
  function getChildren() { return $this->_; }
  function hasParent() { return ( isset( $this->parent )) ? TRUE : FALSE; }
  function getParent() { return ( is_object( $this->parent )) ? $this->parent : NULL; }

  function find( $k ) {
    return ( $this->$k ) ? $this->$k : FALSE;
  }

  function findDeep( $k ) {
    // check children
    $menu = $this->find( $k );
    if( $menu ) return $menu;
    // check the children's children
    foreach( $this->getChildren() as $child ) {
      $menu = $child->findDeep( $k );
      if( $menu ) return $menu;
    }
    return $this;
  }
}

?>
