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
    $page->controller = $controller;
    $page->action = $action;
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
      $html = $page->render();
      if( $page->theme() !== NULL ) {
        $html = theme::load( $page->theme(), $html, TRUE );
      }
      $html = filter::doFilters( $html );
      if( c::get( 'cache' )) cache::set( $cacheID, (string) $html, TRUE );
      // TODO: work on an indexing system to store word counts under a parent URL for a future site search
      if( c::get( 'index' )) index::store( $cacheId, (string) $html );
    } else {
      $html = $cacheData;
      $cclink = url( router::rule( 'flush' ));
      $html = preg_replace( '/<\/body>/i', '<div id="cacheBox" style="display:none;"><a href="'.$cclink.'">Clear Cache</a></div></body>', $html );
    }

    // OUTPUT TO BROWSER
    echo trim( $html );

    exit;
  }

}
?>
