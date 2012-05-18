<?php

// Direct access protection
if( !defined( 'LIRIOPE' )) die( 'Direct access is not allowed.' );

//
// obj
// Liriope's base object, implementing the Iterator
//

class obj implements Iterator {

  public $_ = array();

  function __construct( $array=array() ) {
    $this->_ = $array;
  }

  function __set( $k, $v ) {
    $this->_[$k] = $v;
  }

  function __get( $k ) {
    return a::get( $this->_, $k );
  }

  function __call( $k, $args ) {
    return a::get( $this->_, $k );
  }

  function rewind() {
    reset( $this->_ );
  }

  function current() {
    return current( $this->_ );
  }

  function key() {
    return key( $this->_ );
  }

  function next() {
    return next( $this->_ );
  }

  function prev() {
    return prev( $this->_ );
  }

  function valid() {
    $key = key( $this->_ );
    $v = ($key !== NULL && $key !== FALSE );
    return $v;
  }

  function count() {
    return count( $this->_ );
  }

  function first() {
    return a::first( $this->_ );
  }

  function last() {
    return a::last( $this->_ );
  }

}

?>
