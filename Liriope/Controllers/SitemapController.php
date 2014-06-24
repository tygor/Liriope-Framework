<?php

namespace Liriope\Controllers;

use Liriope\c;
use Liriope\Toolbox\a;
use Liriope\Component\Search\Sitemap;

class SitemapController Extends LiriopeController {

  // @var boolean Whether or not the sitemap.xml file exists
  var $found = FALSE;
  // @var string The XML version
  var $version;
  // @var string The encoding for the XML
  var $encoding;

  /**
   * CONSTRUCTOR
   *
   * @return void
   */
  public function __construct( $controller = 'Sitemap', $action = 'show' ) {
      parent::__construct(new Sitemap(), $controller, $action);

      $this->page = $this->getPage();
      $this->page->setTheme('');

      $this->page->set('version', '1.0');
      $this->page->set('encoding', 'UTF-8');
      $this->page->set('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');
  }

  public function show() {
      return $this->page->render();
  }

} ?>
