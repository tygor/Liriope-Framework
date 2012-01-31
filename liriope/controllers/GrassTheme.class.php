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

    $this->set( 'theme.name', 'Grass' );
    
    // set the theme template file
    #$this->setThemeFile( 'grass.php' );
  }

}
