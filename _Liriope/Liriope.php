<?php
//
// Liriope: a PHP site framework
//
// @version 0.1
// @author Tyler Gordon <tyler@tygorden.com>
// @copyright Copyright 2013 Tyler Gordon
// @license http://www.opensource.org/license/mit-license.php MIT License
// @package Liriope
//

namespace Liriope;

use Liriope\Toolbox\Router;
use Liriope\Component\Load;

// load the configuration class
require_once( dirname(__FILE__).'/Component/Config.php' );
c::set( 'version', 0.1 );
c::set( 'language', 'en' );
c::set( 'charset', 'utf-8' );

// --------------------------------------------------
// main call function
// Begins the framework inner-workings
// --------------------------------------------------
function Liriope() {
  extract( Router::getDispatch() );
  if( strtolower($use) === 'module' ) {
    $liriope = Router::callModule( $controller, $action, $params );
    exit;
  } else {
    $liriope = Router::callController( $controller, $action, $params );
    $liriope->load();
    exit;
  }
}

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
set_exception_handler( 'Liriope\LiriopeException' );

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

// set the root locations
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
unset( $rootWeb );
unset( $rootLiriope );
unset( $rootApplication );

// setup the Liriope path
c::set( 'path', array(
  c::get( 'root' ) . '/library',
  c::get( 'root' ) . '/library/helpers',
  c::get( 'root' ) . '/_Liriope',
  c::get( 'root.application' ) . '/controllers',
  c::get( 'root.application' ) . '/models',
  c::get( 'root.application' ) . '/views',
  c::get( 'root.liriope' ) . '/Controllers',
  c::get( 'root.liriope' ) . '/Models',
  c::get( 'root.liriope' ) . '/views',
  c::get( 'root.content' )
));

// load the must have Liriope objects
require_once( dirname(__FILE__) . '/Component/Load.php' );
spl_autoload_register( 'Liriope\Component\Load::autoload', TRUE );

// TODO: remove the underscore in front of Liriope/vendor...
load::file(c::get('root.liriope').'/../_Liriope/vendor/SplClassLoader.php', TRUE);
$classLoader = new \SplClassLoader('Liriope', realpath(c::get('root.liriope').'/..'));
$classLoader->register();

load::lib();
load::models();
load::helpers();
load::plugins();
load::config();

// switch on error reporting
if( c::get( 'debug' )) {
  error_reporting( E_ALL );
  ini_set( 'display_errors', c::get('errors.display',1) );
} else {
  error_reporting( 0 );
  ini_set( 'display_errors', 0 );
}

// Begin
Liriope();