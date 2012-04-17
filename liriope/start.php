<?php
//
// start.php
//
  error_reporting( E_ALL );
  ini_set( 'display_errors', 1 );

// direct access protection
if( !isset( $root )) die( 'Direct access is not allowed' );

// future direct access protection
define( 'LIRIOPE', true );

// include Liriope
require_once( $rootLiriope . '/library/liriope.php' );

// set the root locations
c::set( 'root',              realpath( $root ));
c::set( 'root.web',          $rootWeb );
c::set( 'root.liriope',      realpath( $rootLiriope ));
c::set( 'root.application',  realpath( $rootApplication ));
c::set( 'root.content',      $rootWeb . '/content' );
c::set( 'root.theme',        $rootWeb . '/themes' );
c::set( 'root.snippets',     $rootWeb . '/snippets' );
c::set( 'root.content.file', 'index' );

// setup the Liriope path
c::set( 'path', array(
  $rootLiriope . '/library',
  $rootLiriope . '/controllers',
  $rootLiriope . '/models',
  $rootLiriope . '/views',
  $rootApplication . '/controllers',
  $rootApplication . '/models',
  $rootApplication . '/views',
  $root . '/library',
  $root . '/library/helpers'
));

// load the rest of the system
require_once( $rootLiriope . '/library/load.class.php' );
load::lib();
load::models();
load::helpers();
load::tools();
spl_autoload_register( 'load::autoload', TRUE );

c::set( 'debug', TRUE );
// switch on error reporting
if( c::get( 'debug' )) {
  error_reporting( E_ALL );
  ini_set( 'display_errors', 1 );
} else {
  error_reporting( 0 );
  ini_set( 'display_errors', 0 );
}

// Begin
callLiriope();

// TODO find a way to report the collected errors

?>
