<?php
//
// liriope.php
//

// direct access protection
if( !isset( $root )) die( 'Direct access is not allowed ~Liriope.' );
// for all other direct access protection, define a constant
define( 'LIRIOPE', true );

// convert index.php set paths to system paths
$root = realpath( $root );
$rootLiriope = realpath( $rootLiriope );
$rootApplication = realpath( $rootApplication );

// Include Liriope
require_once( $rootLiriope . '/library/functions.php' );
require_once( $rootLiriope . '/library/config.class.php' );
require_once( $rootLiriope . '/library/load.class.php' );

// --------------------------------------------------
// Setup an Autoloader
// --------------------------------------------------
spl_autoload_register( 'load::autoload', TRUE );

//
// --------------------------------------------------
// Configuration section
// --------------------------------------------------
// set from index.php
// $root            <-- the starting point for this site
// $rootLiriope     <-- the path to the liriope folder
// $rootApplication <-- the path to this site's application folder
// $rootWeb         <-- the path to this site's web folder
// $dev             <-- Development ON or OFF
//
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
c::set( 'development',       $dev );
c::set( 'root',              $root );
c::set( 'root.web',          $rootWeb );
c::set( 'root.liriope',      $rootLiriope );
c::set( 'root.application',  $rootApplication );
c::set( 'root.content',      $rootWeb . '/content' );
c::set( 'root.theme',        $rootWeb . '/themes' );
c::set( 'root.snippets',     $rootWeb . '/snippets' );
c::set( 'root.content.file', 'index' );

load::lib();
load::models();
load::helpers();
load::tools();

// Load Defaults first the liriope set, then the site set
include( c::get( 'root.liriope' ) . '/defaults.php' );
include( c::get( 'root.application' ) . '/defaults.php' );

// Make it safe
removeMagicQuotes();
unregisterGlobals();

// Set some default router rules
router::setRule( 'blog/show', 'blog/show' );
router::setRule( 'blog/*',    'Liriope/filepage/blog/$1' );
router::setRule( 'features',  'Liriope/filepage/projects' );
router::setRule( '*/*',       'Liriope/filepage' );
router::setRule( '*',         'Liriope/filepage' );

// Begin
callLiriope();

// TODO find a way to report the collected errors

?>
