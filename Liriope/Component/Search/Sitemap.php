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
        $this->setFilename($filename, false);
    }

    // addPage()
    // Add a new page to the sitemap only if it doesn't already exist.
    //
    // @retrun boolean True if the page was added, False if it was not
    //
    public function addPage($loc='http://www.example.com/', $lastmod=NULL, $changefreq='monthly', $priority='0.8') {
        // <loc>http://www.example.com/</loc>
        // <lastmod>2005-01-01</lastmod>
        // <changefreq>monthly</changefreq>
        // <priority>0.8</priority>

        $lastmod = is_null($lastmod) ? date('Y-m-d', time()) : date('Y-m-d', strtotime($lastmod));

        $pageExists = $this->checkForPage($loc);

        if(!$pageExists) {
            array_push($this->pages, array(
                'loc'        => (string) $loc,
                'lastmod'    => (string) $lastmod,
                'changefreq' => (string) $changefreq,
                'priority'   => (string) $priority
            ));

            return true;
        } else {
            // TODO: Decide how to handle "merged" urls in regard to change frequency and priority
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
     * @return mixed The index of the found page, False if it is not, and False if no pages exist
     */
    private function checkForPage($url) {
        if( count($this->pages) < 1) {
            // No URLS are stored in the sitemap flie
            return false;
        }
        foreach($this->pages as $page) {
            if( a::contains($page, $url) ) {
                return true;
            }
        }
        return false;
    }

    /**
     * setFilename()
     * Changes the name of the sitemap file that is created
     *
     * @param string The fielname to use for the XML sitemap
     * @param boolean Whether or not to immediately read that file
     *
     * @return boolean True on success, false on error
     */
    public function setFilename($filename="sitemap.xml", $read=true) {
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
        if($read) {
            $this->read();
        }
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
     * @return  mixed   The number of stored sitmap urls, or FALSE on failure
     */
    public function read() {
        
        // Determine if the file exists
        $file = Load::exists($this->filename, c::get('root.web'));
        
        // If it does not, then create an empty file
        if( !$file ) {
            // File::touch(c::get('root.web') . DIRECTORY_SEPARATOR . $this->filename);
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
