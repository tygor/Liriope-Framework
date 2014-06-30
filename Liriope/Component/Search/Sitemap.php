<?php

namespace Liriope\Component\Search;

use Liriope\c;
use Liriope\Toolbox\a;
use Liriope\Toolbox\File;
use Liriope\Component\Load;
use Liriope\Component\Content\Buffer;

class Sitemap {

    // @var string The relative path to the file
    private $path;

    // @var string The sitemap.xml file name
    private $filename;

    // @var array The array of pages to dump into the sitemap
    private $pages = array();

    public function __construct($filename='sitemap.xml') {
        $this->setFilename($filename);
    }

    // addPage()
    // Add a new page to the sitemap only if it doesn't already exist.
    //
    // @param boolean True if the page was added, False if it was not
    //
    public function addPage($loc='http://www.example.com/', $lastmod='2000-01-01', $changefreq='monthly', $priority='0.8') {
        // <loc>http://www.example.com/</loc>
        // <lastmod>2005-01-01</lastmod>
        // <changefreq>monthly</changefreq>
        // <priority>0.8</priority>

        // TODO: Check to ensure this url location doesn't already exist.
        // TODO: Decide how to handle "merged" urls in regard to change frequency and priority

        if(!$this->checkForPage($loc)) {
            array_push($this->pages, array(
                'loc'        => (string) $loc,
                'lastmod'    => (string) $lastmod,
                'changefreq' => (string) $changefreq,
                'priority'   => (string) $priority
            ));

            return true;
        }
        return false;
    }

    // getPages()
    // Returns the array of pages
    //
    public function getPages() {
        return $this->pages;
    }

    /**
     * checkForPage()
     * Looks in the pages for the existence of the test url.
     *
     * @return boolean True if the page is found, false if it is not.
     */
    private function checkForPage($url) {
        if( count($this->pages < 1)) {
            return -1;
        }
        var_dump(a::search($this->pages, $url));
        return a::search($this->pages, $url);
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

    /**
     * read
     * Reads the current sitemap file and stores its mysteries in the model
     *
     * @return integer The number of stored sitmap urls
     */
    public function read() {
        // Determine if the file exists
        $file = Load::exists($this->filename, c::get('root.web'));
        if( !$file ) {
            return false;
        }
        // Read that file and pass it's contents to the model
        $xml = File::read($file, 'xml');
        if($xml instanceof \SimpleXMLElement) {
            foreach($xml as $url) {
                $this->addPage(
                    a::first((array) $url->loc),
                    a::first((array) $url->lastmod),
                    a::first((array) $url->changefreq),
                    a::first((array) $url->priority)
                );
            }
            return count($xml);
        }
        return false;
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
