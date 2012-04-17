<?php
//
// Liriope: a PHP site framework
//
// @version 0.0
// @author Tyler Gordon <tyler@tygorden.com>
// @copyright Copyright 2012 Tyler Gordon
// @license http://www.opensource.org/license/mit-license.php MIT License
// @package Liriope
//

// load the configuration class
require_once( 'config.class.php' );
c::set( 'version', 0.0 );
c::set( 'language', 'en' );
c::set( 'charset', 'utf-8' );

// --------------------------------------------------
// Setup Exception Handler
// This will be where we define what to do with
// uncaught exceptions.
// --------------------------------------------------
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

function stripSlashesDeep( $value ) {
	$value = is_array( $value ) ? array_map( 'stripSlashesDeep', $value ) : stripslashes( $value );
	return $value;
}

// --------------------------------------------------
// Check for Magic Quotes and remove them
// This detects if magic quotes is enabled, and if so, cleans
// them from the REQUEST variables.
// --------------------------------------------------
function removeMagicQuotes() {
  if ( get_magic_quotes_gpc() ) {
    $_GET    = stripSlashesDeep($_GET   );
    $_POST   = stripSlashesDeep($_POST  );
    $_COOKIE = stripSlashesDeep($_COOKIE);
  }
}
removeMagicQuotes();

// --------------------------------------------------
// Check register globals and remove them
// If superglobals are set, then go ahead and remove $Global values
// --------------------------------------------------
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
unregisterGlobals();

// --------------------------------------------------
// callLiriope
// --------------------------------------------------
// main call function
// Begins the framework inner-workings
//
function callLiriope() {
  extract( router::getDispatch() );
  router::callHook( $controller, $action, $params );
}

// --------------------------------------------------
// useHelper
//
// Look for a helper with the passed $name
// and include the file.
// 
// Helpers are php files containing a set of functions
// to be used in the view templates.
// --------------------------------------------------
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

?>
