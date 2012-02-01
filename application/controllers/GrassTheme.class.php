<?php
/**
 * GrassTheme.class.php
 * --------------------------------------------------
 */

// Direct access protection
if( !defined( 'LIRIOPE' )) die( 'Direct access is not allowed.' );

class GrassTheme extends LiriopeTheme
{
  function __construct()
  {
    parent::start();

    // set default values
    $this->set( 'theme.name', 'Grass' );
    $this->set( 'theme.folder', 'grass' );
    $this->set( 'theme.file', 'grass.php' );
    $this->set( 'page.title', 'Liriope : Monkey Grass' );
  }

}
