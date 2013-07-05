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
    filter::$filters[$slug] = array(
      'func'  => $function,
      'order' => $order
    );
  }

  static function removeFilter( $slug ) {
    unset( filter::$filters[$slug] );
  }

  static function doFilters( $content=NULL ) {
    if( $content === NULL ) return "";

    filter::orderFilters();

    // run the filters
    foreach( filter::$filters as $filter ) {
      $content = call_user_func( $filter['func'], $content );
    }
    return $content;
  }

  static function orderFilters() {
    // run the filters array and arrange them based on order value
    uasort( filter::$filters, 'filter::compareOrder' );
  }

  static private function compareOrder( $a, $b ) {
    if( $a['order'] == $b['order'] ) {
      return 0;
    }
    return ( $a['order'] < $b['order'] ) ? -1 : 1;
  }

}