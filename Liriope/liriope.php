<?php

// Liriope
// @version 0.2BETA
// @author Tyler Gordon <tyler@tygorden.com>
// @copyright Copyright 2012 Tyler Gordon
// @license http://www.opensource.org/license/mit-license.php MIT License
// @package Liriope

if($development) {
	error_reporting( E_ALL );
	ini_set( 'display_errors', 1 );
} else {
  error_reporting( 0 );
  ini_set( 'display_errors', 0 );
}

// direct access protection
if( !isset( $root )) die( 'Direct access is not allowed' );

// future direct access protection
define( 'LIRIOPE', true );

// load the configuration class
require_once( 'Library/Config.php' );

// set some ground-work
c::set( 'version', 0.2 );
c::set( 'language', 'en' );
c::set( 'charset', 'utf-8' );

// secondary call function
// uses the alternative controller, component
function module( $controller, $action, $params=array() ) {
  return router::callModule( $controller, $action, $params );
}

// --------------------------------------------------
// Setup Exception Handler
// This will be where we define what to do with
// uncaught exceptions.
// --------------------------------------------------
function LiriopeException( $exception ) {
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
// useHelper
//
// Look for a helper with the passed $name
// and include the file.
// 
// Helpers are php files containing a set of functions
// to be used in the view templates.
// --------------------------------------------------
function useHelper( $name=NULL ) {
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

// set the root locations
c::set( 'root',                 realpath( $root ));
c::set( 'root.web',             $rootWeb );
c::set( 'root.liriope',         realpath( $rootLiriope ));
c::set( 'root.application',     realpath( $rootApplication ));
c::set( 'root.content',         $rootWeb . '/content' );
c::set( 'root.cache',           $rootWeb . '/cache' );
c::set( 'root.index',           $rootWeb . '/index' );
c::set( 'theme.folder',         'themes' );
c::set( 'root.theme',           $rootWeb . '/themes' );
c::set( 'root.snippets',        $rootWeb . '/snippets' );
c::set( 'root.content.file',    'index' );

// clean up unsed variables
unset($root, $rootWeb, $rootLiriope, $rootApplication);

// setup the Liriope path
c::set( 'path', array(
  c::get( 'root' ) . '/library',
  c::get( 'root' ) . '/library/helpers',
  c::get( 'root.application' ) . '/controllers',
  c::get( 'root.application' ) . '/models',
  c::get( 'root.application' ) . '/views',
  c::get( 'root.liriope' ) . '/Library',
  c::get( 'root.liriope' ) . '/controllers',
  c::get( 'root.liriope' ) . '/models',
  c::get( 'root.liriope' ) . '/views',
  c::get( 'root.content' )
));

// load the rest of the system
require_once( c::get( 'root.liriope' ) . '/Library/Load.php' );
load::lib();
load::models();
load::helpers();
load::plugins();
load::config();
spl_autoload_register( 'load::autoload', TRUE );

load::file(c::get('root.liriope').'/vendor/SplClassLoader.php', TRUE);
$classLoader = new SplClassLoader('Liriope', realpath(c::get('root')));
$classLoader->register();

// Begin
function Liriope() {
  extract( router::getDispatch() );
  if( strtolower($use) === 'module' ) {
    $liriope = router::callModule( $controller, $action, $params );
    exit;
  } else {
    $liriope = router::callController( $controller, $action, $params );
    $liriope->load();
    exit;
  }
}
Liriope();
