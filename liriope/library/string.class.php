<?php

// Direct access protection
if( !defined( 'LIRIOPE' )) die( 'Direct access is not allowed.' );

class str {

  // html
  // converts the string into html-safe string
  //
  // @param  string  $string The string to convert
  // @param  bool    $preserve TRUE to keep HTML tags, and FALSE to convert them
  // @return string  The cleaned html string
  //
  static function html( $string, $preserve=TRUE ) {
    if( $preserve ) {
      return stripslashes( implode( '', preg_replace( '/^([^<].+[^>])$/e', "htmlentities('$1', ENT_COMPAT, 'utf-8')", preg_split('/(<.+?>)/', $string, -1, PREG_SPLIT_DELIM_CAPTURE))));
    } else {
      return htmlentities( $string, ENT_COMPAT, 'utf-8' );
    }
  }

  static function replace( $search, $replace, $source ) {
    return str_replace( $search, $replace, $source);
  }

  static function minus( $source, $search ) {
    return self::replace( $search, '', $source );
  }
}

?>
