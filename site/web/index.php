<?php

// --------------------------------------------------
// Web root of your site
// --------------------------------------------------
// this should be the same as the directory where
// index.php is located.
//
// You will likely not need to change this setting
//
$rootWeb = dirname(__FILE__);

// --------------------------------------------------
// Liriope framework folder
// --------------------------------------------------
// this is the path to where the liriope framework
// files are kept. By default it is ../liriope but you
// can move the folder and change the reference here
// so that multiple sites can be run from one installation.
//
$rootLiriope = realpath('../../Liriope');

// --------------------------------------------------
// Site application folder
// --------------------------------------------------
// this is the path to where the logic of the site is stored
// usually /application under which is the /controllers, /models
// and /views folders.
//
$rootApplication = realpath('../../site');

// Load Liriope: Monkey Grass
if( !file_exists( $rootLiriope . '/Liriope.php' )) {
  die( 'The Liriope framework could not be found.' );
}

require_once( $rootLiriope . '/Liriope.php' );

?>
