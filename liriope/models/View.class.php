<?php

// Direct access protection
if( !defined( 'LIRIOPE' )) die( 'Direct access is not allowed.' );

//
// View.class.php
// handles throwing to HTML
//

load::file( load::exists( 'Obj.class.php'), TRUE );

class View extends obj {

  // the controller to call
  var $_controller;

  // the action to call
  var $_action;

  // the page object
  var $_page;

  var $cacheEnabled = FALSE;

  function __construct( $controller, $action ) {
    global $site;
    $site = new Site();

    $this->_controller = $controller;
    $this->_action = $action;
    $file = load::exists( $controller . '/' . $action . '.php' );
    if( !$file ) trigger_error( "We can't find that view file: $file", E_USER_ERROR );

    $page = new Page( $file );
    $page->controller = $controller;
    $page->action = $action;
    $page->uri = uri::get();
    $page->theme = c::get('theme');
    $this->_page = &$page;
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
    $this->_page->setTheme($file);
  }

  // load()
  // Render the output directly to the page or optionally return the
  // generated output to caller (which never happens).
  //
  function load() {
    global $site;
    $page = &$this->_page;

    if( c::get( 'debug' )) theme::set( 'error', error::render( TRUE ));
    
    // CACHE
    // ----------
    $cache = NULL;
    $cacheModified = time();
    $cacheID = uri::md5URI();

    if( c::get( 'cache' )) {
      $cacheModified = cache::modified( $cacheID );
      // if the cache file is newer than all of the content files, then use the cache
      if( $cacheModified >= dir::modified( c::get( 'root.content' )))
        $cacheData = cache::get( $cacheID, TRUE );
    }

    if( empty( $cacheData )) {
      $content_html = $page->render();
      if( $page->theme() !== NULL ) {
        $html = theme::load( $page->theme(), array( 'page'=>$page, 'content'=>$content_html ), TRUE );
        $html = filter::doFilters( $html );
        if( c::get( 'cache' )) { cache::set( $cacheID, (string) $html, TRUE ); }
        if( c::get( 'index' )) { index::store( uri::get(), (string) $html, (string) $content_html ); }
      } else {
        // if the theme is set to null, simply return the content html
        $html = $content_html;
      }
    } else {
      $html = $cacheData;
      if( c::get('debug')) {
        $cclink = url( router::rule( 'flush' ));
        $html = preg_replace( '/<\/body>/i', '<div id="cacheBox" style="display:none;"><a href="'.$cclink.'">Clear Cache</a></div></body>', $html );
      }
    }

    // OUTPUT TO BROWSER
    echo trim( $html );
  }

}
?>
