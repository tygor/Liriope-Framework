<?php

// Direct access protection
if( !defined( 'LIRIOPE' )) die( 'Direct access is not allowed.' );

// Include the configuration and load class
require_once( $rootLiriope . '/library/config.class.php' );
require_once( $rootLiriope . '/library/load.class.php' );

// set the default path and default controller name
c::set( 'path', array(
  $root . '/application/controllers',
  $root . '/application/models',
  $root . '/application/views',
  $root . '/library',
  $root . '/library/helpers'
  ));
c::set( 'controller.default', 'default' );
c::set( 'action.default',     'show' );

/*
 * Setup an Autoloader
 * --------------------------------------------------
 */

spl_autoload_register( function ( $className ) { 
  
  // Apply the naming convention
  $className = ucfirst( $className ) . '.class.php';

  // find out if the file exists
  try {
    if( !load::seek( $className )) throw new Exception( 'Unable to find the ' . $className . ' Object in the SERVER_ROOT' );
  } catch( Exception $e ) {
      header("HTTP/1.0 500 Internal Server Error");
      echo $e->getMessage();
      exit;
  }
  return true; 
}); 

/**
 * callLiriope()
 * Main Call Function
 * 
 * Begins the framework inner-workings
 */
function callLiriope() {
  extract( router::destructURI() );
  try {
    router::callHook( $controller, $action, $getVars );
  } catch ( Exception $e ) {
    die( "Caught Exception in " . __FUNCTION__ . ": " . $e->getMessage() );
  }
}

?>
