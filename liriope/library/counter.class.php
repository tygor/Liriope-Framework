<?php

// Direct access protection
if( !defined( 'LIRIOPE' )) die( 'Direct access is not allowed.' );

class counter {
  static $_;
  static $cycle;

  static function init( $cycle=2 ) {
    self::$_ = 1;
    self::$cycle = $cycle;
  }

  static function test( $num ) {
    return self::$_ % $num;
  } 
  static function first() {
    if( self::$_ == 0 || self::test( self::$cycle ) == 1 ) return TRUE;
    return FALSE;
  }
  static function last() {
    if( self::$_ != 0 && self::test( self::$cycle ) == 0 ) return TRUE;
    return FALSE;
  }
  static function cycle( $num ) { self::$cycle = $num; }
  static function add() { self::$_++; }
  static function subtract() { self::$_--; }
}

?>