<?php

// Direct access protection
if( !defined( 'LIRIOPE' )) die( 'Direct access is not allowed.' );

//
// menu
//

class menu extends obj {
  var $label;
  var $url;

  public function __construct( $label='Home', $url='home' ) {
    $this->label = $label;
    $this->url = $url;
    return $this;
  }
  public function addChild( $label, $url ) {
    $menu = new menu( $label, $url );
    $this->_[ $label ] = $menu;
    return $this;
  }
  public function hasChildren() {
    if( count( $this->_ ) >= 1 ) return true;
    return false;
  }
  public function getChildren() {
    return $this->_;
  }
  public function find( $k ) {
    return a::get( $this->_, $k );
    return $this->get( $label, 'nothing found' );
  }
}

?>
