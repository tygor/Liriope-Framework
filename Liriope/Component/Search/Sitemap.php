<?php

namespace Liriope\Component\Search;

use Liriope\c;
use Liriope\Toolbox\File;
use Liriope\Component\Load;
use Liriope\Component\Content\Buffer;

class Sitemap {

    // @var string The site's base URL
    var $base;

    // @var array The array of pages to dump into the sitemap
    var $pages = array();

    public function __construct() {
        $this->base = c::get('url');
    }

    // addPage()
    // Add a new page to the sitemap.
    //
    // @param  string  $loc  The URL of the page
    //
    public function addPage($loc) {
        $this->pages[] = $this->base . '/' . $loc;

        /*
        var_dump($this->pages);
        exit;
        */
    }

    public function save() {
        $template = Load::exists('Resources/views/Sitemap/template.php');
        $sitemap = Buffer::get($template, array('locations' => $this->pages));
        $success = File::write(c::get('root.web') . '/sitemap.xml', $sitemap);
        if(!$success) {
            trigger_error('The sitemap.xml file was not saved.');
            return false;
        }
        return true;
    }

} ?>
