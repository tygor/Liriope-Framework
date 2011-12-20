<?php
/**
 * Liriope Load
 */

# --------------------------------------------------
# Constants
# --------------------------------------------------
define( 'DEVELOPMENT_ENVIRONMENT', true );
defined( 'DS' ) ? null : define( 'DS', DIRECTORY_SEPARATOR );
define( 'SERVER_ROOT', realpath( dirname(__FILE__) . DS . '..' ) );
// TODO: the SITE_ROOT needs to be defined in a better way
// but for now, I'll hard-code it
define( 'SITE_ROOT', 'http://liriope.ubuntu' );
define( 'WEB_PATH', realpath( SERVER_ROOT . DS . 'web' ) );

# --------------------------------------------------
# Grab the required files
# --------------------------------------------------
require_once( SERVER_ROOT . DS . 'config' . DS . 'LiriopeRouter.php' );
include_once( SERVER_ROOT . DS . 'library' . DS . 'helpers' . DS . 'LiriopeHelpers.php' );

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

setReporting();
removeMagicQuotes();
unregisterGlobals();
callHook();

