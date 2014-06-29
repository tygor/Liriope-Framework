<?php

namespace Liriope\Component\Search;

use Liriope\c;
use Liriope\Toolbox\File;
use Liriope\Component\Load;
use Liriope\Component\Content\Buffer;

class Sitemap {

    // @var string The site's base URL
    var $base;

    // @var string The relative path to the file
    private $path;

    // @var string The sitemap.xml file name
    private $filename;

    // @var array The array of pages to dump into the sitemap
    private $pages = array();

    public function __construct($filename='sitemap.xml') {
        $this->filename = $filename;
        $this->base = c::get('url');
    }

    // addPage()
    // Add a new page to the sitemap only if it doesn't already exist.
    //
    // @param  string  $loc  The URL of the page
    //
    public function addPage($loc='http://www.example/com/', $lastmod='2000-01-01', $changefreq='monthly', $priority='0.8') {
        // <loc>http://www.example.com/</loc>
        // <lastmod>2005-01-01</lastmod>
        // <changefreq>monthly</changefreq>
        // <priority>0.8</priority>

        // TODO: Check to ensure this url location doesn't already exist.
        // TODO: Decide how to handle "merged" urls in regard to change frequency and priority
        // TODO: Allow for a fully-qualified location to be passed as well as a relative file URL.

        array_push($this->pages, array(
            'loc' => $this->base . DIRECTORY_SEPARATOR . $loc,
            'lastmod' => $lastmod,
            'changefreq' => $changefreq,
            'priority' => $priority
        ));

        return true;
    }

    // getPages()
    // Returns the array of pages
    //
    public function getPages() {
        return $this->pages;
    }

    // readSitemap()
    // Reads the current sitemap.xml file so that it can be added to.
    //
    public function readSitemap() {
    }

    /**
     * setFilename()
     * Changes the name of the sitemap file that is created
     *
     * @return boolean True on success, false on error
     */
    public function setFilename($filename="sitemap.xml") {
        // Bail if empty
        if( empty($filename) ) {
            return false;
        }

        // Ensure the file parts
        // Look for a filename
        $name = pathinfo($filename, PATHINFO_FILENAME);
        // Look for an extension
        $extension = pathinfo($filename, PATHINFO_EXTENSION);

        if( empty($name) || empty($extension) ) {
            return false;
        }

        $this->filename = $filename;
        return true;
    }

    /**
     * getFilename()
     * Returns the currently set filename
     *
     * @return string The path and filename of the current sitemap file
     */
    public function getFilename() {
        return $this->filename;
    }

    public function getPath($withFile=true) {
        if($withFile) {
            return c::get('root.web') . DIRECTORY_SEPARATOR . $this->filename;
        } else {
            return c::get('root.web');
        }
    }

    public function save($output) {
        $success = File::write(c::get('root.web') . DIRECTORY_SEPARATOR . $this->filename, $output);
        if(!$success) {
            trigger_error('The sitemap.xml file was not saved.');
            return false;
        }
        return true;
    }

} ?>
