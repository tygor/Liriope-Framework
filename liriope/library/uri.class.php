<?php
/* --------------------------------------------------
 * uri.class.php
 * --------------------------------------------------
 */

// Direct access protection
if( !defined( 'LIRIOPE' )) die( 'Direct access is not allowed.' );

class uri {

  static protected $route;

  static function getURI() {
    if( empty( self::$route )) {
      $route = $_SERVER['REQUEST_URI'];
      $route = server::get( 'REQUEST_URI' );
      // clean up leading and trailing slashes
      $route = trim( $route, '/' );
      self::$route = $route;
    }
    return self::$route;
  }

  static function getURIArray( $cleanRewrite=TRUE) {
    $route = self::getURI();
    $route = explode( '/', $route);

    if( $cleanRewrite && strtolower( $route[0] ) == 'index.php' ) {
      array_shift( $route );
    }

    return (array) $route;
  }

} ?>
