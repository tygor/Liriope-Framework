<?php

// Direct access protection
if( !defined( 'LIRIOPE' )) die( 'Direct access is not allowed.' );

//
// View.class.php
// handles throwing to HTML
//

class View extends obj {
  var $_controller;
  var $_action;

   function __construct( $controller, $action ) {
    global $site;
    $site = new Site();

    $this->_controller = $controller;
    $this->_action = $action;
    $file = load::exists( $controller . '/' . $action . '.php' );
    if( !$file ) trigger_error( "We can't find that view file: $file", E_USER_ERROR );

    global $page;
    $page = new Page( $file );
    $page->uri = uri::get();
    $page->theme = c::get('theme');
	}

  // view()
  // changes the view file to use
  //
  // @param  string  $file The file to use instead of the predetermined one
  // @return bool    TRUE on success, FALSE on error
  function view( $file=NULL ) {
    if( $file===NULL ) return FALSE;
    $check = $this->_controller . '/' . $this->_action . '_' . $file . '.php';
    $file = load::exists( $check );
    if( !$file ) return FALSE;
    global $page;
    $page->_view = $file;
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
    
    // CACHE
    // ----------
    $cache = NULL;
    $cacheModified = time();
    $cacheID = uri::md5URI();

    if( c::get( 'cache' )) {
      $cacheModified = cache::modified( $cacheID );
      if( $cacheModified >= dir::modified( c::get( 'root.content' )))
        $cacheData = cache::get( $cacheID, TRUE );
    }

    if( empty( $cacheData )) {
      $html = theme::load( $page->theme(), $page->render(), TRUE );
      if( c::get( 'cache' )) cache::set( $cacheID, (string) $html, TRUE );
    } else {
      $html = $cacheData;
    }

    // OUTPUT TO BROWSER
    echo trim( $html );

    exit;
  }

}
?>
