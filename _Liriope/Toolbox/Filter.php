<?php

namespace Liriope\Toolbox;

/**
 * The Filters class handles the registering and executing of functions prior
 * to the View dumping the output buffer.
 */

class Filter {

  // @var array The array of filters to run
  static $filters = array();

  /**
   * Adds a function to the filter chain
   *
   * @param string $slug     The unique text to refer to the filter by
   * @param string $function The name of the function to call
   * @param int    $order    The placement in the order of operations
   *
   * @return void
   */
  static function addFilter( $slug, $function, $order=NULL ) {
    self::$filters[$slug] = array(
      'func'  => $function,
      'order' => $order
      );
  }

  /**
   * Removes a function to the filter chain
   *
   * @param string $slug The unique text to refer to the filter by
   *
   * @return void
   */
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

  /**
   * Comparison function
   */
  static private function compareOrder( $a, $b ) {
    if( $a['order'] == $b['order'] ) {
      return 0;
    }
    return ( $a['order'] < $b['order'] ) ? -1 : 1;
  }

}
