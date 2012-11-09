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

  function __construct( $label='Home', $url='home', $depth=1 ) {
    $this->label = $label;
    $this->url = $url;
    $this->depth = $depth;
    $this->active = 0;
    $this->current = 0;
    return $this;
  }

  function __toString() {
    return $this->url;
  }

  function loadFromYaml($file) {
    $yaml = new Yaml($file);
    
    $this->isActive();
    // parse through the nested arrays and convert to a parent/child menu
    foreach( $yaml->parse(TRUE) as $item ) {
      $this->addChild($item['label'], $item['url']);
      $this->loadChildren($item);
    }
    return $this;
  }

  private function loadChildren($item) {
    if(!isset($item['children'])) return FALSE;
    $parent = $this->find($item['url']);
    foreach($item['children'] as $item) {
      $parent->addChild($item['label'], $item['url']);
      $parent->loadChildren($item);
    }
  }

  function trim($depth) {
    if(!$depth) return $this;
    $trimDepth = $this->depth + $depth;
    $this->trimDeep($trimDepth);
  }

  private function trimDeep($depth) {
    foreach($this->getChildren() as $child) {
      $child->trimDeep($depth);
    }
    if($this->depth >= $depth) {
      $this->_ = array();
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

  //
  // getCurrent()
  // returns the menu item that is marked as current
  //
  function getCurrent() {
echo " $this ";
    foreach($this->getChildren() as $child ) {
      if($child->current) {
        return $child;
      }
      $child->getCurrent();
    }
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

  function hasParent() { return ( !empty( $this->parent )) ? TRUE : FALSE; }
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
