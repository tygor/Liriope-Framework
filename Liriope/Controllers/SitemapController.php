<?php

namespace Liriope\Controllers;

use Liriope\c;
use Liriope\Toolbox\a;
use Liriope\Component\Search\Sitemap;
use Liriope\Toolbox\File;

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

        $this->model = new Sitemap();
        $this->model->addPage();
        $this->model->addPage();

        $this->page = $this->getPage();
        $this->page->setTheme('');

        $this->page->set('version', '1.0');
        $this->page->set('encoding', 'UTF-8');
        $this->page->set('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');
    }

    /**
     * setFilename()
     * Changes the name of the sitemap file that is created
     *
     * @return boolean True on success, false on error
     */
    public function setFilename($filename) {
        if( !is_null($filename)) {
            return $this->model->setFilename($filename);
        }
        return false;
    }

    /**
     * show
     * Renders the sitemap file by returning a string
     *
     * @return string The rendered sitemap.xml
     */
    public function show() {
        $this->page->set('urls', $this->model->getPages());
        $return = $this->page->render();
        return $return;
    }

    /**
     * save
     * Save the rendered sitemap to file
     *
     * @return boolean True on succes, false on error
     */
    public function save() {
        return $this->model->save($this->show());
    }

    /**
     * remove
     * Deletes the sitemap file
     *
     * @return boolean True on succes, false on error
     */
    public function remove() {
        return File::remove($this->model->getPath());
    }

} ?>
