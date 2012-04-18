<?php

// Direct access protection
if( !defined( 'LIRIOPE' )) die( 'Direct access is not allowed.' );

//
// obj
// Liriope's base object, implementing the Iterator
//

class obj implements Iterator {

  public $iterate = array();

  function __construct( $array=array() ) {
    $this->iterate = $array;
  }

  function __set( $k, $v ) {
    $this->iterate[$k] = $v;
  }

  function __get( $k ) {
    return a::get( $this->iterate, $k );
  }

  function __call( $k, $args ) {
    return a::get( $this->iterate, $k );
  }

  function rewind() {
    reset( $this->iterate );
  }

  function current() {
    return current( $this->iterate );
  }

  function key() {
    return key( $this->interate );
  }

  function next() {
    return next( $this->iterate );
  }

  function prev() {
    return prev( $this->iterate );
  }

  function valid() {
    $key = key( $this->iterate );
    $v = ($key !== NULL && $key !== FALSE );
    return $v;
  }

}

?>
