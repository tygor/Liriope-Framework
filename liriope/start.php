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
unset( $root );
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
  c::get( 'root.liriope' ) . '/library',
  c::get( 'root.liriope' ) . '/controllers',
  c::get( 'root.liriope' ) . '/models',
  c::get( 'root.liriope' ) . '/views',
  c::get( 'root.content' )
));

// load the rest of the system
require_once( c::get( 'root.liriope' ) . '/library/load.class.php' );
spl_autoload_register( 'load::autoload', TRUE );

// TODO: remove the underscore in front of Liriope/vendor...
load::file(c::get('root.liriope').'/../_Liriope/vendor/SplClassLoader.php', TRUE);
$classLoader = new SplClassLoader('Liriope', realpath(c::get('root.liriope').'/..'));
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

?>
