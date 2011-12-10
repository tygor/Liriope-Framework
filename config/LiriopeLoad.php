<?php
/**
 * Liriope Load
 */

# --------------------------------------------------
# Constants
# --------------------------------------------------
defined( 'DS' ) ? null : define( 'DS', DIRECTORY_SEPARATOR );
define( 'APPLICATION_PATH', realpath( dirname(__FILE__) . DS . '..' ) );
define( 'WEB_PATH', realpath( APPLICATION_PATH . DS . 'web' ) );

# --------------------------------------------------
# Grab the required files
# --------------------------------------------------
require_once( APPLICATION_PATH . DS . 'config' . DS . 'config.php' );

# --------------------------------------------------
# Setup an Autoloader
# --------------------------------------------------
spl_autoload_register( function ( $className ) { 
    $possibilities = array( 
        APPLICATION_PATH.DS.'application'.DS.'controllers'.DS.$className.'.class.php', 
        APPLICATION_PATH.DS.'application'.DS.'models'     .DS.$className.'.class.php', 
        APPLICATION_PATH.DS.'application'.DS.'views'      .DS.$className.'.class.php', 
        APPLICATION_PATH.DS.'library'                     .DS.$className.'.class.php', 
        $className.'.class.php' 
    ); 
    foreach( $possibilities as $file ) { 
        if( file_exists( $file )) { 
            require_once( $file ); 
            return true; 
        } 
    } 
		/* Error Generation Code Here */
    return false; 
}); 

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
    ini_set('error_log', APPLICATION_PATH . DS . 'tmp' . DS . 'logs' . DS . 'error.log');
  }
}

# --------------------------------------------------
# Check for Magic Quotes and remove them
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

# --------------------------------------------------
# Main Call Function
# --------------------------------------------------
$url = $_SERVER['REQUEST_URI'];

function callHook() {
	global $url;

	$urlArray = array();
	$urlArray = explode("/",$url);

  # The first item in the array is blank due to a leading '/'
  # so let's just cut that off
  array_shift( $urlArray );

  # The first part of the url after index.php will be the name
  # of the controller, but it it's empty, then we're most likely
  # on the homepage so use default
  $controller = !empty( $urlArray[0] ) ? $urlArray[0] : 'default';
  array_shift($urlArray);

  # If there is another piece to the url, it's the action
  # but if not, then default to the show action
    $action = !empty( $urlArray[0] ) ? $urlArray[0] : 'show';
  if( !empty( $urlArray[0] ))
  {
    array_shift($urlArray);
  }

  # Now that the controller and action are popped off the of array
  # the remaining should be considered query string in name = value pairs
  $queryString = $urlArray;

	$controllerName = $controller;
	$controller = ucwords( LiriopeTools::cleanInput( $controller, 'alphaOnly' ));
	$model = rtrim($controller, 's');
	$controller .= 'Controller';
	$dispatch = new $controller($model,$controllerName,$action);

	if ((int)method_exists($controller, $action)) {
		call_user_func_array(array($dispatch,$action),$queryString);
	} else {
		/* Error Generation Code Here */
	}
}

setReporting();
removeMagicQuotes();
unregisterGlobals();
callHook();

