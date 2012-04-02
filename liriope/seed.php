<?php
//
// seed.php
//
// Called directly from index.php in the web folder.
// Seed is the begining of the greater plant.
//
// TODO: Is seed.php necessary? Why not put all of this
// in liriope.php?

// direct access protection
if( !isset( $root )) die( 'Direct access is not allowed ~Liriope.' );
// for all other direct access protection, define a constant
define( 'LIRIOPE', true );

// Include Liriope
require_once( $rootLiriope . '/library/config.class.php' );
require_once( $rootLiriope . '/library/load.class.php' );
require_once( $rootLiriope . '/library/liriope.php' );

//
// --------------------------------------------------
// Configuration section
// --------------------------------------------------
//
c::set( 'path', array(
  $root . '/liriope/library',
  $root . '/liriope/controllers',
  $root . '/liriope/models',
  $root . '/application/controllers',
  $root . '/application/models',
  $root . '/application/views',
  $root . '/library',
  $root . '/library/helpers'
  ));
// grab the variables set in index.php
c::set( 'development', $dev );
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

// Load Defaults
include( 'defaults.php' );

// Make it safe
setReporting();
removeMagicQuotes();
unregisterGlobals();

// Begin
callLiriope();

// TODO find a way to report the collected errors

?>
