<?php

/* --------------------------------------------------
 * Server root of your site
 * --------------------------------------------------
 * this should be the same as the directory where
 * index.php is located
 *
 */
$root = dirname(__FILE__);

/* --------------------------------------------------
 * Liriope framework folder
 * --------------------------------------------------
 * this is the path to where the liriope framework
 * files are kept. By default it is ../liriope but you
 * can move the folder and change the reference here
 * so that multiple sites can be run from one installation.
 *
 */
$rootLiriope = $root . '/../liriope';


// Load Liriope: Monkey Grass
if( !file_exists( $rootLiriope . '/LiriopeLoad.php' )) {
  die( 'The Liriope framework could not be loaded.' );
}

require_once( $rootLiriope . '/LiriopeLoad.php' );

?>
