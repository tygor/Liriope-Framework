<?php

// --------------------------------------------------
// Web root of your site
// --------------------------------------------------
// this should be the same as the directory where
// index.php is located.
//
$rootWeb = dirname(__FILE__);

// --------------------------------------------------
// Server root of your site
// --------------------------------------------------
// This is where the the meat of the application
// code lives.
//
$root = realpath( $rootWeb . '/../..' );

// --------------------------------------------------
// Liriope framework folder
// --------------------------------------------------
// this is the path to where the liriope framework
// files are kept. By default it is ../liriope but you
// can move the folder and change the reference here
// so that multiple sites can be run from one installation.
//
$rootLiriope = $root . '/Liriope';

// --------------------------------------------------
// Site application folder
// --------------------------------------------------
// this is the path to where the logic of the site is stored
// usually /application under which is the /controllers, /models
// and /views folders.
//
$rootApplication = $root . '/site';

// --------------------------------------------------
// Developement switch
// --------------------------------------------------
// Turn the switch on to enable development helpers
// 
$development = true;

// Load Liriope: Monkey Grass
if( !file_exists( $rootLiriope . '/liriope.php' )) {
  die( 'The Liriope framework could not be found.' );
}

require_once( $rootLiriope . '/liriope.php' );

?>
