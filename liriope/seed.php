<?php

// direct access protection
if( !isset( $root )) die( 'Direct access is not allowed ~Liriope.' );

// used for direct access protection
define( 'LIRIOPE', true );

// Include Liriope
require_once( $rootLiriope . '/library/liriope.php' );

// enable development
c::set( 'development', $dev );

// set the root
c::set( 'root',          $root );
c::set( 'root.web',      $rootWeb );
c::set( 'root.liriope',  $rootLiriope );
c::set( 'root.content',  $rootWeb . '/content' );
c::set( 'root.content.file', 'index' );
c::set( 'root.theme',    $rootWeb . '/themes' );
c::set( 'root.snippets', $rootWeb . '/snippets' );

load::lib();
load::models();
load::helpers();
load::tools();

include( 'defaults.php' );

// ----------------------------------------------------------------------------------------------------

# --------------------------------------------------
# Setup Exception Handler
# This will be where we define what to do with
# uncaught exceptions.
# --------------------------------------------------
function LiriopeException( $exception )
{
  //error message
  $errorMsg = '<b>Liriope Exception:</b> Error on line '.$exception->getLine().' in '.$exception->getFile()
  .': <b>'.$exception->getMessage().'</b>';
  echo $errorMsg . "<br/>\n";
  echo "<pre>";
  echo $exception->getTraceAsString();
  echo "</pre>";
  exit;
}
set_exception_handler( 'LiriopeException' );

# --------------------------------------------------
# Check if environment is development and display errors
# --------------------------------------------------
function setReporting() {
  if ( c::get( 'development' ) )
  {
    error_reporting(E_ALL);
    ini_set('display_errors','On');
  }
  else
  {
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
  try {
    if( !load::seek( $helperName )) throw new Exception( 'Unable to find that helper: ' . $helperName );
  } catch( Exception $e ) {
      header("HTTP/1.0 500 Internal Server Error");
      echo $e->getMessage();
      echo "<pre>";
      var_dump( $e->getTrace());
      echo "</pre>";
      exit;
  }
}

setReporting();
removeMagicQuotes();
unregisterGlobals();

// Begin
callLiriope();

?>
