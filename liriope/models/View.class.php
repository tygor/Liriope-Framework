<?php

// Direct access protection
if( !defined( 'LIRIOPE' )) die( 'Direct access is not allowed.' );

//
// View.class.php
// handles throwing to HTML
//

class View extends obj {

   function __construct( $controller, $action ) {
    global $site;
    $site = new Site();

    $file = load::exists( $controller . '/' . $action . '.php' );
    if( !$file ) trigger_error( "We can't find that view file: $file", E_USER_ERROR );

    global $page;
    $page = new Page( $file );
    $page->uri = uri::get();
    $page->theme = c::get('theme');
	}

  // load()
  // Render the output directly to the page or optionally return the
  // generated output to caller (which never happens).
  //
  function load() {
    global $site;
    global $page;

    // tell the theme object about the site and the page
    theme::set( 'site', $site );
    theme::set( 'page', $page );
    if( c::get( 'debug' )) theme::set( 'error', error::render( TRUE ));

    $html = theme::load( $page->theme(), $page->render(), TRUE );

    // OUTPUT TO BROWSER
    echo trim( $html );

    exit;
  }

}

?>
