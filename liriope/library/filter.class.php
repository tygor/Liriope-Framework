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
    // TODO: add in a way to mess with the order
    self::$filters[] = array(
      'slug' =>     $slug,
      'function' => $function,
      'order' =>    $order
    );
  }

  static function doFilters( $content=NULL ) {
    if( $content === NULL ) return "";

    // run the filters
    foreach( self::$filters as $filter ) {
      $content = call_user_func( $filter['function'], $content );
    }
    return $content;
  }

}
?>
