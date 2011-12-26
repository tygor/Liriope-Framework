<?php
/**
 * Liriope Load
 * The spark into life:
 *
 * In a backyard full of leaves and weeds, there was a *pop* and evolution
 * occured. Out of the primordial filth--compost--came the first steps
 * to becoming a website: Liriope (aka monkey grass). Yes, evolution
 * from monkies.
 */

# --------------------------------------------------
# Constants
# --------------------------------------------------
define( 'DEVELOPMENT_ENVIRONMENT', true );
defined( 'DS' ) ? null : define( 'DS', DIRECTORY_SEPARATOR );
define( 'SERVER_ROOT', realpath( dirname(__FILE__) . DS . '..' ) );
// TODO: the SITE_ROOT needs to be defined in a better way
// but for now, I'll hard-code it. I'd like it to be selected
// from a config file.
// On my desktop: liriope.ubuntu
// on my laptop:  liriope.local
define( 'SITE_ROOT', 'http://liriope.local' );
define( 'WEB_PATH', realpath( SERVER_ROOT . DS . 'web' ) );

# --------------------------------------------------
# Setup an Autoloader
# --------------------------------------------------
/**
 * seekFile
 *
 * Checks the framework path for the given $file
 */
function seekFile( $file )
{
  $paths = array( 
      SERVER_ROOT.DS.'application'.DS.'controllers'.DS,
      SERVER_ROOT.DS.'application'.DS.'models'     .DS,
      SERVER_ROOT.DS.'application'.DS.'views'      .DS,
      SERVER_ROOT.DS.'library'                     .DS,
      SERVER_ROOT.DS.'library'.DS.'helpers'        .DS,
  ); 
  try {
    $loaded = false;
    foreach( $paths as $path ) { 
        if( file_exists( $path . $file )) { 
            $loaded = $path . $file;
        } 
    } 
    if( !$loaded ) {
      throw new Exception( 'Liriope was unable to find ' . $file . '.' );
    }
  } catch( Exception $e ) {
      header("HTTP/1.0 500 Internal Server Error");
      echo $e->getMessage();
      exit;
  }
  return $loaded; 
}

/**
 * spl_autoload_register
 * my autoloader
 */
spl_autoload_register( function ( $className ) { 
  // Apply the naming convention
  $className = ucfirst( $className ) . '.class.php';
  // find out if the file exists
  try {
    $loaded = false;
    if( $file = seekFile( $className )) {
      require_once( $file );
      $loaded = true;
    }
    if( !$loaded ) {
      throw new Exception( 'Unable to find the ' . $className . ' Object in the SERVER_ROOT' );
    }
  } catch( Exception $e ) {
      header("HTTP/1.0 500 Internal Server Error");
      echo $e->getMessage();
      exit;
  }
  return true; 
}); 

# --------------------------------------------------
# Grab the required files
# --------------------------------------------------
require_once( SERVER_ROOT . DS . 'config' . DS . 'LiriopeRouter.php' );

# --------------------------------------------------
# Check if environment is development and display errors
# --------------------------------------------------
function setReporting() {
  if (DEVELOPMENT_ENVIRONMENT == true) {
    error_reporting(E_ALL);
    ini_set('display_errors','On');
  } else {
    error_reporting(E_ALL);
    ini_set('display_errors','Off');
    ini_set('log_errors', 'On');
    ini_set('error_log', SERVER_ROOT . DS . 'tmp' . DS . 'logs' . DS . 'error.log');
  }
}

# --------------------------------------------------
# Check for Magic Quotes and remove them
# This detects if magic quotes is enabled, and if so, cleans
# them from the REQUEST variables.
# --------------------------------------------------
function stripSlashesDeep( $value ) {
	$value = is_array( $value ) ? array_map( 'stripSlashesDeep', $value ) : stripslashes( $value );
	return $value;
}

function removeMagicQuotes() {
  if ( get_magic_quotes_gpc() ) {
    $_GET    = stripSlashesDeep($_GET   );
    $_POST   = stripSlashesDeep($_POST  );
    $_COOKIE = stripSlashesDeep($_COOKIE);
  }
}

# --------------------------------------------------
# Check register globals and remove them
# If superglobals are set, then go ahead and remove $Global values
# --------------------------------------------------
function unregisterGlobals() {
    if (ini_get('register_globals')) {
        $array = array('_SESSION', '_POST', '_GET', '_COOKIE', '_REQUEST', '_SERVER', '_ENV', '_FILES');
        foreach ($array as $value) {
            foreach ($GLOBALS[$value] as $key => $var) {
                if ($var === $GLOBALS[$key]) {
                    unset($GLOBALS[$key]);
                }
            }
        }
    }
}


/**
 * useHelper
 *
 * Look for a helper with the passed $name
 * and include the file.
 * 
 * Helpers are php files containing a set of functions
 * to be used in the view templates.
 */
function useHelper( $name=NULL )
{
  // rename "default" to "liriope"
  if( strtolower( $name ) == 'default' ) $name = 'Liriope';
  // work with the $name to follow the naming convention
  $helperName = ucfirst( $name ) . 'Helpers.php';
  // find out if the file exists
  if( $file = seekFile( $helperName )) {
    include_once( $file );
    return true;
  }
  /* TODO: error handling */
  die( 'We can\'t find the helper file' );
}

setReporting();
removeMagicQuotes();
unregisterGlobals();
callLiriope();

