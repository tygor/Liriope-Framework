<?php

// Direct access protection
if( !defined( 'LIRIOPE' )) die( 'Direct access is not allowed.' );

//
// menu
//

load::file( load::exists( 'Obj.class.php'), TRUE );

class menu extends obj {
  var $depth;
  var $label;
  var $url;
  var $parent;
  var $active;
  var $current;

  function __construct( $label=NULL, $url=NULL, $depth=1 ) {
    $this->label = $label;
    $this->url = $url;
    $this->depth = $depth;
    $this->active = 0;
    $this->current = 0;
    return $this;
  }

  function loadFromYaml($file) {
    $yaml = new Yaml($file);
    
    foreach( $yaml->parse(TRUE) as $v ) {
      $this->addChild( $v['label'], $v['url'] );
      if( isset( $v['children'] )) {
        $parent = $this->find( $v['url'] );
        foreach( $v['children'] as $c ) {
          $parent->addChild( $c['label'], $c['url'] );
        }
      }
    }
  }

  function setActive() {
    if( !$this->isActive() ) $this->isActive( TRUE );
  }

  function isActive( $inherit=FALSE ) {
    if( $this->active ) return $this->active;
    // mark the menu object and any parent which matches the current URI
    if( $inherit || $this->url == uri::get() ) {
      if( $this->hasParent() ) $this->getParent()->isActive( TRUE );
      $this->active = TRUE;
      // mark the menu object which matches the current URI
      if( $this->url === uri::get() ) {
        $this->current = TRUE;
      }
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

  function getCurrent() {
    // usually run from the parent. Finds the menu object marked $current
    if( $this->current ) return $this;
    foreach($this->getChildren() as $child ) {
      if( $child->current ) return $child;
      $child->getCurrent();
    }
    return FALSE;
  }

  function addChild( $label, $url ) {
    $menu = new menu( $label, $url, $this->depth+1 );
    $menu->parent = $this;
    $menu->isActive();
    $this->$url = $menu;
    return $this;
  }

  function hasChildren() { return ( count( $this->_ ) > 0 ) ? TRUE : FALSE; }

  function getChildren( $depth=FALSE ) {
    if( !$depth ) return $this->_;
    if( $this->depth <= $depth ) return $this->_;
    return array();
  }

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
