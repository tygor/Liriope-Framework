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
  c::get( 'root.application' ) . '/controllers',
  c::get( 'root.application' ) . '/models',
  c::get( 'root.application' ) . '/views',
  c::get( 'root.liriope' ) . '/library',
  c::get( 'root.liriope' ) . '/controllers',
  c::get( 'root.liriope' ) . '/models',
  c::get( 'root.liriope' ) . '/views'
));

// load the rest of the system
require_once( c::get( 'root.liriope' ) . '/library/load.class.php' );
load::lib();
load::models();
load::helpers();
load::config();
spl_autoload_register( 'load::autoload', TRUE );

// switch on error reporting
if( c::get( 'debug' )) {
  error_reporting( E_ALL );
  ini_set( 'display_errors', 1 );
} else {
  error_reporting( 0 );
  ini_set( 'display_errors', 0 );
}

// Begin
Liriope();

?>
