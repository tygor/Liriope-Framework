<?php
/* --------------------------------------------------
 * slot.class.php
 * --------------------------------------------------
 *
 */

// Direct access protection
if( !defined( 'LIRIOPE' )) die( 'Direct access is not allowed.' );

class slot {

  static function define( $name=NULL ) {
    if( $name===NULL ) return false;
  }

  static function addContent( $content=NULL, $slot=NULL ) {
    if( $content===NULL || $slot===NULL ) return false;
  }

}
