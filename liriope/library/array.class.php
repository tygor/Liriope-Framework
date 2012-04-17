<?php

// Direct access protection
if( !defined( 'LIRIOPE' )) die( 'Direct access is not allowed.' );

//
// Array handeling class
//

class a {

  //
  // get()
  // returns and element of an array by the key
  //
  // @param  array  $array The array to look within
  // @param  mixed  $key   The key to look for
  // @param  mixed  $default Optional value to return if the key isn't found
  // @return mixed
  //
  static function get( $array, $key, $default=NULL ) {
    return isset( $array[$key] ) ? $array[$key] : $default;
  }

  //
  // getAll()
  // returns multiple values from the array $keys
  //
  // @param  array  $array The array to look within
  // @param  array  $keys   The key to look for
  // @return array  An array of sought keys and their values
  //
  static function getAll( $array, $keys ) {
    $r = array();
    foreach( $keys as $key ) {
      $r[$key] = $array[$key];
    }
    return $r;
  }

  //
  // show()
  // displays an array in a way readable for debugging
  //
  // @param  array  $array The array to be shown
  // @param  bool   $print (default) TRUE echoes the result, FALSE returns the result
  // @return mixed  No return if $print is TRUE, otherwise, returns a string
  //
  static function show( $array, $print=TRUE ) {
    $r = "<pre>";
    $r .= htmlspecialchars( var_dump( $array ));
    $r .= "</pre>";
    if( $print === TRUE ) echo $r;
    return $r;
  }
}

?>
