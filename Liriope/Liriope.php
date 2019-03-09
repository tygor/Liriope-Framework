<?php
//
// Liriope: a PHP site framework
//
// @version 0.4BETA
// @author Tyler Gordon <tyler@tygorden.com>
// @copyright Copyright 2013 Tyler Gordon
// @license http://www.opensource.org/license/mit-license.php MIT License
// @package Liriope
//

namespace Liriope;

use Liriope\Toolbox\Router;
use Liriope\Component\Load;

date_default_timezone_set('America/New_York');

// load the configuration class
require_once( dirname(__FILE__).'/Component/Config.php' );
c::set( 'version', 0.4 );
c::set( 'language', 'en' );
c::set( 'charset', 'utf-8' );

// --------------------------------------------------
// main call function
// Begins the framework inner-workings
// --------------------------------------------------
function Liriope($route=NULL) {
  $route = $route ?: Router::getDispatch();
  if(is_callable($route)) {
    $route();
    exit;
  }
  extract( $route );
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
  $value = is_array( $value ) ? array_map( 'Liriope\stripSlashesDeep', $value ) : stripslashes( $value );
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

// set the root locations
c::set( 'root.web',          $rootWeb );
c::set( 'root.liriope',      realpath( $rootLiriope ));
c::set( 'root.application',  realpath( $rootApplication ));
c::set( 'root.content',      $rootWeb . '/content' );
c::set( 'root.cache',        $rootWeb . '/cache' );
c::set( 'root.index',        $rootWeb . '/index' );
c::set( 'theme.folder',      'themes' );
c::set( 'root.theme',        $rootWeb . '/themes' );
c::set( 'root.snippets',     $rootWeb . '/snippets' );
c::set( 'root.content.file', 'index' );

// clean up unused variables
unset( $rootWeb );
unset( $rootLiriope );
unset( $rootApplication );

// setup the Liriope path
c::set( 'path', array(
  c::get( 'root.application' ) . '/controllers',
  c::get( 'root.application' ) . '/models',
  c::get( 'root.application' ) . '/views',
  c::get( 'root.liriope' ),
  c::get( 'root.liriope' ) . '/Controllers',
  c::get( 'root.liriope' ) . '/Models',
  c::get( 'root.liriope' ) . '/Views',
  c::get( 'root.content' )
));

// Get the autloader working
require_once( dirname(__FILE__) . '/Component/Load.php' );
Load::file(c::get('root.liriope').'/../Liriope/vendor/SplClassLoader.php', TRUE);

$appLoader = new \SplClassLoader(NULL, realpath(c::get('root.application').'/controllers'));
$appLoader->register();

$classLoader = new \SplClassLoader('Liriope', realpath(c::get('root.liriope').'/..'));
$classLoader->register();

// Preload some common Liriope objects
Load::lib();
Load::models();
Load::helpers();
Load::plugins();
Load::config();

// switch on error reporting
if( c::get( 'debug' )) {
  error_reporting( E_ALL );
  ini_set( 'display_errors', c::get('errors.display',1) );
} else {
  error_reporting( 0 );
  ini_set( 'display_errors', 0 );
}

// Check for a shell script command
if(defined('SHELL_COMMAND')) {
    if(SHELL_COMMAND == 'crawl') {
        Liriope(array('controller'=>'liriope','action'=>'crawl','params'=>array(),'use'=>'module'));
    }
    // just in case the app doesn't have a previous exit;
    echo "\nFIN\n";
    exit;
}

// Begin
Liriope();
