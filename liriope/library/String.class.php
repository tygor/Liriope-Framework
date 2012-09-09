<?php

// Direct access protection
if( !defined( 'LIRIOPE' )) die( 'Direct access is not allowed.' );

class String {
  // the string
  var $_;

  // an instance of the string for non-destructive manipulation
  var $instance;

  public function __construct( $string=NULL ) {
    if($string===NULL) return NULL;
    $this->_ = $string;
  }

  public function __toString() {
    return $this->get();
  }

  public function raw() {
    return $this->_;
  }

  public function peek() {
    return $this->getInstance();
  }

  public function get() {
    $return = &$this->getInstance();
    unset( $this->instance );
    return $return;
  }

  private function &getInstance() {
    if(!isset($this->instance)) {
      $this->instance = $this->_;
    }
    return $this->instance;
  }

/*
 --------------------------------------------------
 Chainable methods
 --------------------------------------------------
 */

  public function to_lowercase() {
    $i = &$this->getInstance();
    $i = strtolower($i);
    return $this;
  }

  public function reverse() {
    $i = &$this->getInstance();
    $i = strrev($i);
    return $this;
  }

  public function trim( $charlist=' ') {
    $i = &$this->getInstance();
    $i = trim($i, $charlist);
    return $this;
  }

  // to_html
  // converts the string into html-safe string
  //
  // @param  bool    $preserve TRUE to keep HTML tags, and FALSE to convert them
  //
  public function to_html( $preserve=TRUE ) {
    $i = &$this->getInstance();
    if( $preserve ) {
      $i = stripslashes( implode( '', preg_replace( '/^([^<].+[^>])$/e', "htmlentities('$1', ENT_COMPAT, 'utf-8')", preg_split('/(<.+?>)/', $i, -1, PREG_SPLIT_DELIM_CAPTURE))));
    } else {
      $i = htmlentities( $i, ENT_COMPAT, 'utf-8' );
    }
    return $this;
  }

  // replace
  // replace strings in a subject with another string
  //
  public function replace( $search, $replace ) {
    $i = &$this->getInstance();
    $i = str_replace($search, $replace, $i);
    return $this;
  }

  // minus
  // remove string from a subject
  //
  public function minus( $search ) {
    $i = &$this->getInstance();
    $this->replace( $search, '' );
    return $this;
  }

  // words
  // remove everything that is not a word
  //
  public function words($glue=' ') {
    $i = &$this->getInstance();
    $i = preg_replace( '/[^\pL]/i', $glue, $i );
    $i = preg_replace( '/[,]+/', $glue, $i );
    $this->trim();
    return $this;
  }

  // addslashes
  //
  public function addslashes() {
    $i = &$this->getInstance();
    if(get_magic_quotes_gpc()) { addslashes($i); }
    return $this;
  }

  // stripslashes
  // magic quote test, then strip slashes
  //
  public function stripslashes() {
    $i = &$this->getInstance();
    if(get_magic_quotes_gpc()) { stripslashes(stripslashes($i)); }
    return $this;
  }

  // link_urls
  //
  public function link_urls() {
    $i = &$this->getInstance();
    $pattern = '#((http|https|ftp)[:]//[a-z0-9-.]+\.[a-z]{2,3}(:[a-z0-9]*)?/?([a-z0-9-._\?,\'/\\\+&%\$\#\=~])*[^.,\)\(\s])#i';
    $count = preg_match_all( $pattern, $i, $matches );
    if( !$count ) return $this; 

    $wrapper = '<a href="%s" target="_blank">%s</a>';
    $patterns = array();
    $replacements = array();
    foreach( $matches[1] as $match) {
      $patterns[] = '#' . preg_quote($match, '#') . '#i';
      $replacements[] = sprintf( $wrapper, $match, $match );
    }
    $i = preg_replace( $patterns, $replacements, $i );
    return $this;
  }

  // rot()
  // rotate the characters as if in a circle
  //
  public function rot( $n=13 ) {
    $i = &$this->getInstance();
    static $letters = 'AaBbCcDdEeFfGgHhIiJjKkLlMmNnOoPpQqRrSsTtUuVvWwXxYyZz';
    $n = (int)$n % 26;
    if (!$n) return $this;
    if ($n < 0) $n += 26;
    if ($n == 13) {
      $i = str_rot13($i);
    } else {
      $rep = substr($letters, $n * 2) . substr($letters, 0, $n * 2);
      $i = strtr($i, $letters, $rep);
    }
    return $this;
  }

/*
 --------------------------------------------------
 Methods that return a result rather than the object
 --------------------------------------------------
 */

  // split
  // alias for PHP explode()
  // turn a string into an array on the passed character
  //
  // @param  string  $split The string to break apart on
  // @param  int     $limit The smallest allowed string length
  // @return array   Returns the resulting array rather than $this for chaining
  //
  public function split( $split=',', $limit=1 ) {
    $i = &$this->getInstance();
    if(is_array($i)) {
      return $this;
    }
    $this->trim();
    $parts = explode($split, $i);
    $out = array();
    foreach( $parts as $p ) {
      $p = trim($p);
      if(mb_strlen($p, 'UTF-8') > 0 && mb_strlen($p, 'UTF-8') >= $limit) {
        $out[] = $p;
      }
    }
    $i = (array) $out;
    return $i;
  }

  // parse()
  // parse the passed content with the chosen parse method
  //
  public function parse( $mode='json' ) {
    $i = &$this->getInstance();
    if( is_array( $i )) return $this;
    switch( $mode ) {
      case 'json':
        $result = (array) @json_decode( $i, TRUE );
        break;
      case 'url':
        $result = (array) @parse_url( $i );
        break;
      case 'php':
        $result = @unserialize( $i );
        break;
      default:
        $result = $i;
        break;
    }
    return $result;
  }

}
