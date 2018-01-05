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

        $this->page = $this->getPage();
        $this->page->setTheme('');

        $this->page->set('version', '1.0');
        $this->page->set('encoding', 'UTF-8');
        $this->page->set('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');
    }

    /**
     * clearPages()
     * Tells the model to remove any stored pages.
     *
     * @return  boolean True on succes, False on failure.
     */
    public function clearPages() {
        return $this->model->flush();
    }

    /**
     * addPage()
     * Adds a new page to the sitemap model
     * NOTE: This will not automatically save to file.
     *
     * @return  boolean True if the page was added, False if it already existed (passed attributes overwrite that page)
     */
    public function addPage($loc=NULL, $lastmod=NULL, $changefreq=NULL, $priority=NULL) {
        return $this->model->addPage($loc, $lastmod, $changefreq, $priority);
    }

    /**
     * removePage()
     * Removes a page from the sitemap model
     * NOTE: This will not automatically save to file.
     *
     * @return  boolean     True if the page was removed, False on failure
     */
    public function removePage($loc=NULL) {
        return $this->model->removePage($loc);
    }

    /**
     * countPages()
     * Return the number of pages in the sitemap.
     *
     * @return  integer     The number of pages that exist in the sitemap
     */
    public function countPages() {
        return $this->model->countPages();
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
