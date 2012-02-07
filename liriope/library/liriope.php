<?php

// Direct access protection
if( !defined( 'LIRIOPE' )) die( 'Direct access is not allowed.' );

// Include the configuration and load class
require_once( $rootLiriope . '/library/config.class.php' );
require_once( $rootLiriope . '/library/load.class.php' );

// set the default path and default controller name
c::set( 'path', array(
  $root . '/liriope/library',
  $root . '/liriope/controllers',
  $root . '/liriope/models',
  $root . '/application/controllers',
  $root . '/application/models',
  $root . '/application/views',
  $root . '/library',
  $root . '/library/helpers'
  ));

/* --------------------------------------------------
 * Load Defaults
 * --------------------------------------------------
 */
c::set( 'default.controller', 'default' );
c::set( 'default.action',     'show' );
c::set( 'default.theme',      'Grass' );

/* --------------------------------------------------
 * Setup an Autoloader
 * --------------------------------------------------
 */
spl_autoload_register( function ( $className ) { 
  
  // Apply the naming convention
  $className = ucfirst( $className ) . '.class.php';

  // find out if the file exists
  try {
    if( !load::seek( $className )) throw new Exception( 'Unable to find the ' . $className . ' object with the autoloader.' );
  } catch( Exception $e ) {
      header("HTTP/1.0 500 Internal Server Error");
      echo $e->getMessage();
      echo "<hr><pre>";
      echo $e->getTraceAsString();
      echo "</pre><hr>";
      exit;
  }
  return true; 
}); 

/* --------------------------------------------------
 * callLiriope
 * --------------------------------------------------
 * main call function
 * Begins the framework inner-workings
 */
function callLiriope() {
  @extract( router::destructURI() );
  router::callHook( $controller, $action, $getVars );
}

?>
