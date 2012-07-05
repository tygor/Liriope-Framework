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

  // split()
  // turn a string into an array on the passed character
  static function split( $string, $split=',', $length=1 ) {
    if( is_array( $string )) return $string;
    $string = trim( $string, $split );
    $parts = explode( $split, $string );
    $out = array();
    foreach( $parts as $p ) {
      $p = trim( $p );
      if( mb_strlen( $p, 'UTF-8' ) > 0
        && mb_strlen( $p, 'UTF-8' ) >= $length ) {
        $out[] = $p;
      }
    }
    return $out;
  }

  // replace()
  // replace strings in a subject with another string
  //
  static function replace( $search, $replace, $source ) {
    return str_replace( $search, $replace, $source);
  }

  // minus()
  // remove string from a subject
  //
  static function minus( $source, $search ) {
    return self::replace( $search, '', $source );
  }

  // rot()
  // rotate the characters as if in a circle
  //
  static function rot( $s, $n=13 ) {
    static $letters = 'AaBbCcDdEeFfGgHhIiJjKkLlMmNnOoPpQqRrSsTtUuVvWwXxYyZz';
    $n = (int)$n % 26;
    if (!$n) return $s;
    if ($n < 0) $n += 26;
    if ($n == 13) return str_rot13($s);
    $rep = substr($letters, $n * 2) . substr($letters, 0, $n * 2);
    return strtr($s, $letters, $rep);
  }

  // stripToWords()
  // remove everything that is not a word
  //
  static function stripToWords( $content ) {
    $content = preg_replace( '/[^\pL]/i', ',', $content );
    $content = preg_replace( '/[,]+/', ',', $content );
    return trim( $content );
  }

  // stripslashes()
  // magic quote test, then strip slashes
  //
  static function stripslashes( $string ) {
    if( is_array( $string )) return $string;
    return (get_magic_quotes_gpc()) ? stripslashes( stripslashes( $string )) : $string;
  }

  // parse()
  // parse the passed content with the chosen parse method
  //
  static function parse( $string, $mode='json' ) {
    if( is_array( $string )) return $string;
    switch( $mode ) {
      case 'json':
        $result = (array) @json_decode( $string, TRUE );
        break;
      case 'url':
        $result = (array) @parse_url( $string );
        break;
      case 'php':
        $result = @unserialize( $string );
        break;
      default:
        $result = $string;
        break;
    }
    return $result;
  }

}

?>
