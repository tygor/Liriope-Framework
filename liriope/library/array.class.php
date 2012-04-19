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
    $r .= htmlspecialchars( print_r( $array, true ));
    $r .= "</pre>";
    if( $print === TRUE ) echo $r;
    return $r;
  }

  //
  // search()
  // search for values in an array by regular expression
  //
  // @param  array  $array The array to look in
  // @param  string $search The regular expression
  // @return array  The array of results
  //
  static function search( $array, $search ) {
    return preg_grep( "#" . preg_quote( $search ) . "#i", $array );
  }

  //
  // contains()
  // Yes/No if an array has a value
  //
  // @param  array  $array The array to look in
  // @param  string $search The needle to look for
  // @return bool   TRUE if found, FALSE otherwise
  //
  static function contains( $array, $search ) {
    $search = self::search( $array, $search );
    return empty( $search ) ? FALSE : TRUE;
  }

  //
  // first()
  // Shift an element off of the beginning of an array
  //
  // @param  array  $array The array to mutliate
  // @return mixed  The first element of an array
  //
  static function first( $array ) {
    return array_shift( $array );
  }

  //
  // last()
  // Pop an element off of the ending of an array
  //
  // @param  array  $array The array to mutliate
  // @return mixed  The last element of an array
  //
  static function last( $array ) {
    return array_pop( $array );
  }

}

?>
