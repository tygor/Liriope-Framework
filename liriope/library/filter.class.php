<?php
// --------------------------------------------------
// filter.class.php
// --------------------------------------------------

// Direct access protection
if( !defined( 'LIRIOPE' )) die( 'Direct access is not allowed.' );

// the filter class is a holder for all the filters used on the content before it is
// rendered. This can be anything from email abstraction, curse word eliminaiton,
// or wrapping my name in <b> tags.

class filter {
  static $filters = array();

  static function addFilter( $slug, $function, $order=NULL ) {
    self::$filters[$slug] = array(
      'func'  => $function,
      'order' => $order
    );
  }

  static function removeFilter( $slug ) {
    unset( self::$filters[$slug] );
  }

  static function doFilters( $content=NULL ) {
    if( $content === NULL ) return "";

    self::orderFilters();

    // run the filters
    foreach( self::$filters as $filter ) {
      $content = call_user_func( $filter['func'], $content );
    }
    return $content;
  }

  static function orderFilters() {
    // run the filters array and arrange them based on order value
    uasort( self::$filters, 'self::compareOrder' );
  }

  static private function compareOrder( $a, $b ) {
    if( $a['order'] == $b['order'] ) {
      return 0;
    }
    return ( $a['order'] < $b['order'] ) ? -1 : 1;
  }

}

//
// fancyFramework
// --------------------------------------------------
// wrap any instance of "Liriope Framework" with
// a span class of fancy-framework
function fancyFramework( $c ) {
  $pattern = '/(Liriope Framework)/';
  $replacement = '<span class="fancy-framework">$1</span>';
  return preg_replace( $pattern, $replacement, $c );
}
filter::addFilter( 'fancyFramework', 'fancyFramework' );

?>
