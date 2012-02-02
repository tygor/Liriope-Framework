<?php
/**
 * GrassTheme.class.php
 * --------------------------------------------------
 */

// Direct access protection
if( !defined( 'LIRIOPE' )) die( 'Direct access is not allowed.' );

class GrassTheme extends theme
{
  static function start() {
    parent::start();

    self::set( 'theme.name', 'Grass' );
    //self::set( 'theme.folder', 'grass' );
    //self::set( 'theme.file', 'grass.php' );
  }
}
