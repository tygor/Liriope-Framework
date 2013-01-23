<?php

namespace Liriope\Component\Counter;

class Counter {
  static $_;
  static $cycle;

  /**
   * Start the counter passing the cycle amount
   *
   * @param  int  $cycle The number to count to before looping.
   * @return bool Returns TRUE on sucess, FALSE on fail.
   */
  static function init( $cycle=2 ) {
    self::$_ = 1;
    self::$cycle = $cycle;
    return TRUE;
  }

  /**
   * Test an integer against the counter's cycle number.
   * A return of 0 means that the cycle is at the end
   *
   * @param  int  $num An integer to test against the current cycle number
   * @return int  Returns the remainder of the cycle count divided by the test $num passed
   */
  static function test( $num ) {
    return self::$_ % $num;
  } 

  /**
   * Tests if the cycle count is at the begining of the loop
   *
   * @return bool Returns TRUE if the cycle is on the first of a loop, or FALSE if not.
   */
  static function first() {
    if( self::$_ == 0 || self::test( self::$cycle ) == 1 ) return TRUE;
    return FALSE;
  }

  /**
   * Tests if the cycle count is at the end of the loop
   *
   * @return bool Returns TRUE if the cycle is on the last of a loop, or FALSE if not.
   */
  static function last() {
    if( self::$_ != 0 && self::test( self::$cycle ) == 0 ) return TRUE;
    return FALSE;
  }

  /**
   * Sets the cycle amount of each loop
   *
   * @return void
   */
  static function cycle( $num ) { self::$cycle = $num; }

  /**
   * Increments the cycle one step
   *
   * @return void
   */
  static function add() { self::$_++; }

  /**
   * Decrements the cycle one step
   *
   * @return void
   */
  static function subtract() { self::$_--; }
}

