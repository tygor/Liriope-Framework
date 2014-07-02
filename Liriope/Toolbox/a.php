<?php

namespace Liriope\Toolbox;

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

  /**
   * search()
   * search for values in an array by regular expression
   *
   * @param  array  $array The array to look in
   * @param  string $search The regular expression
   *
   * @return array  The array of results
   */
  static function search( $array, $search ) {
    if( !is_array($array) || !is_string($search)  ) {
        if( !is_array($array) ) {
            throw new \Exception('Liriope Array object error with the search() method. The search set passed was not an array.');
        }
        if( !is_string($search) ) {
            throw new \Exception('Liriope Array object error with the search() method. The text to look for was not a string.');
        }
        return false;
    }
    if( count($array) < 1) {
        return false;
    }
    return preg_grep( "#" . preg_quote( $search ) . "#i", $array );
  }

  //
  // searchKeys()
  // search for keys in an array by regular expression
  // matches whole words only
  //
  // @param  array  $array The array to look in
  // @param  string $search The regular expression
  // @return array  The array of results
  //
  static function searchKeys( $array, $search ) {
    $keys = preg_grep( "#\b" . preg_quote( $search ) . "\b#i", array_keys( $array ));
    $vals = array();
    foreach( $keys as $key ) {
      $vals[$key] = $array[$key];
    }
    return $vals;
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

  //
  // glue()
  // glues together an array into a string
  //
  // @param  array  $array The array to glue
  // @param  string $glue The glue between each array item
  // @return string The glued array
  //
  static function glue( $array, $string=NULL ) {
    if( $string === NULL ) return implode( $array );
    return implode( $string, $array );
  }

  // combine()
  // combine two arrays ignoring the keys
  //
  static function combine() {
    $return = array();
    foreach( func_get_args() as $array ) {
      if(!is_array($array)) {
          $return[] = $array;
          continue;
      }
      foreach( $array as $v ) $return[] = $v;
    }
    return $return;
  }

  //
  // toObject()
  //
  static function toObject( $array ) {
    if( !is_array( $array )) return $array;
    $object = new \stdClass();
    if( is_array( $array ) && count( $array ) > 0 ) {
      foreach( $array as $k => $v ) {
        $k = strtolower( trim( $k ));
        $object->$k = a::toObject( $v );
      }
      return $object;
    }
    return FALSE;
  }

  //
  // trim()
  // run the trim function on each value in the array
  //
  // @param  array  $array
  // @param  mixed  $charlist The characters to trim
  // @return array  the array, trimmed
  static function trim( $array, $charlist=' ' ) {
    if( !is_array( $array )) return trim( $array, $charlist );
    foreach( $array as $k => $v ) {
      $array[$k] = trim( $v, $charlist );
    }
    return $array;
  }

  // rewind()
  //
  static function rewind( $array )  {
    reset( $array );
    return $array;
  }

  // fastforward()
  //
  static function fastforward( $array )  {
    end( $array );
    return $array;
  }

  // unfold()
  // takes a key, value paired array and unfolds it into a new array of all
  // values (keys become values in order)
  //
  static function unfold( $array ) {
    $new = array();
    foreach( $array as $k => $v ) {
      $new[] = $k;
      $new[] = $v;
    }
    return $new;
  }

}
