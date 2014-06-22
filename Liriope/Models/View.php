<?php

namespace Liriope\Models;

use Liriope\Component\Content\Page;
use Liriope\Component\Load;
use Liriope\Component\Search\Index;
use Liriope\Component\Search\Sitemap;
use Liriope\Models\Obj;
use Liriope\Models\Theme;
use Liriope\Toolbox\Uri;
use Liriope\Toolbox\Filter;
use Liriope\Toolbox\Site;
use Liriope\Toolbox\Router;

/**
 * View class
 * Handles throwing to HTML
 */

class View extends obj {

  // @var string The controller to call
  var $_controller;

  // @var string The action to call
  var $_action;

  // @var \Liriope\Component\Content\Page The page object
  var $_page;

  // @var boolean Cache flag (default FALSE)
  var $cacheEnabled = FALSE;

  /**
   * CONSTRUCTOR
   *
   * @param string $controller The name of the controller to use before rendering
   * @param string $action     The name of the method in the controller to call
   *
   * @return void
   */
  public function __construct( $controller, $action ) {
    global $site;
    $site = new Site();

    $this->_controller = $controller;
    $this->_action = $action;
    $seek = ucfirst($controller) . '/' . $action . '.php';
    $file = Load::exists($seek);
    if( !$file ) throw new \Exception("We can't find that view file: $file");

    $page = new Page( $file );
    $page->controller = $controller;
    $page->action = $action;
    $page->uri = Uri::get();
    $page->setTheme(\c::get('theme'));
    $this->_page = &$page;
    $this->sitemap = new Sitemap();
  }

  // load()
  // Render the output directly to the page or optionally return the
  // generated output to caller (like for modules or partials).
  //
  function load() {
    global $site;
    $page = &$this->_page;
    $html = false;

    // CACHE
    // ----------
    $cache = NULL;
    $cacheModified = time();
    $cacheID = Uri::md5URI();
    $cacheExpiredTime = \c::get('cache.expiration', (24*60*60));

    // if cache is enabled
    if(\c::get('cache')) {
      $cacheModified = cache::modified( $cacheID );

      // if the cache file is newer than all of the content files...
      if( $cacheModified >= dir::modified( \c::get( 'root.content' ))) {

        // if the cache file created time is within the expiration time
        if(!cache::expired($cacheID, $cacheExpiredTime)) {

          // then, get the cached page
          $html = cache::get( $cacheID, TRUE );

        }
      }
    }

    // If no cache was loaded, then process the page.
    if( !$html ) {

      // first, render the page portion of the view to HTML
      $html = $page->render();

      if( $page->getTheme() !== NULL ) {
        // Load the theme and pass in the page content
        $html = theme::load( $page->getTheme(), array( 'page'=>$page, 'content'=>$html ), TRUE );
        // Run the $html through any final filters that were stored
        $html = Filter::doFilters( $html );
        // if cache is turned on, then cache this page.
        if( \c::get( 'cache' )) {
          cache::set( $cacheID, (string) $html, TRUE );
        }
        // TODO: $page->is404 must be an overloaded variable.
        //       Is this a useless check?
        if( \c::get( 'index' ) && !$page->is404 ) {
          Index::store( Uri::get(), (string) $html, (string) $html );
        }
        $this->sitemap->addPage(Uri::get());
      }

      // Add a clear cache button if the configuration is set to [debug]
      if( \c::get('debug')) {
        $cclink = url( router::rule( 'flush' ));
        $html = preg_replace( '/<\/body>/i', '<div id="cacheBox" style="display:none;"><a href="'.$cclink.'">Clear Cache</a></div></body>', $html );
      }

    }

    // OUTPUT TO BROWSER
    echo trim( $html );
  }

}
