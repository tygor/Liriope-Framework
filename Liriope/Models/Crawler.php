<?php

namespace Liriope\Models;

use Liriope\c;
use Liriope\Component\Search\Index;
use Liriope\Component\Search\Sitemap;

// 
// Crawler
// 
// To ensure that this will work in your dev environment
// set the vhost in your hosts file to 127.0.0.1
// 
class Crawler {

    // this is the root of the site
    static $root;

    // stores the found urls
    static $urls = array();

    // stores the urls that have been crawled already
    static $visited = array();

    // stores the internal urls
    static $internal = array();

    // position of the visited pointer
    static $pos = 0;

    // stores the external urls (though I'm not sure how to use these yet)
    static $external = array();

    // Sitemap object
    static $sitemap;

    static function crawl() {
        self::$root = c::get('url');
        $page = self::getPage('/');

        // Create an instance of the Sitemap model
        self::$sitemap = new Sitemap();

        // index the home page
        self::visit( 'home', $page );
        self::getHREF($page);
        self::traverse();
        self::$sitemap->save();
        return self::$visited;
    }

    static function crawl_page($url, $depth = 5) {
        static $seen = array();
        if (isset($seen[$url]) || $depth === 0) {
            return;
        }

        $seen[$url] = true;

        $dom = new \DOMDocument('1.0');
        @$dom->loadHTMLFile($url);

        $anchors = $dom->getElementsByTagName('a');
        foreach ($anchors as $element) {
            $href = $element->getAttribute('href');
            if (0 !== strpos($href, 'http')) {
                $path = '/' . ltrim($href, '/');
                if (extension_loaded('http')) {
                    $href = http_build_url($url, array('path' => $path));
                } else {
                    $parts = parse_url($url);
                    $href = $parts['scheme'] . '://';
                    if (isset($parts['user']) && isset($parts['pass'])) {
                        $href .= $parts['user'] . ':' . $parts['pass'] . '@';
                    }
                    $href .= $parts['host'];
                    if (isset($parts['port'])) {
                        $href .= ':' . $parts['port'];
                    }
                    $href .= $path;
                }
            }
            crawl_page($href, $depth - 1);
        }
        echo "URL:",$url,PHP_EOL,"CONTENT:",PHP_EOL,$dom->saveHTML(),PHP_EOL,PHP_EOL;
    }

    static function traverse() {
        while( $i = self::nextplease() ) {
            $page = self::getPage( $i );
            self::visit( $i, $page );
            self::getHREF($page);
        }
    }

    static function nextplease() {
        // get current() of the internal and move pointer
        if( isset(self::$internal[self::$pos] )) {
            $current = self::$internal[self::$pos];
            if(isset($current) && !in_array( $current, self::$visited)) {
                self::$pos++;
                return $current;
            }
        }
        return false;
    }

    static function visit( $id, $html ) {
        // read the index.ignore config array and skip those pages
        $ignore = c::get('index.ignore');
        foreach($ignore as $i) {
            // replace all '*' with the any character regex
            $i = preg_quote($i,'/');
            $i = str_replace('\*', '.*', $i);
            if(preg_match("/^$i/is", $id) === 1) return false;
        }

        if(!in_array($id, self::$visited)) {
            // add the page to the visited list
            self::$visited[] = $id;
            // create an index of the page content for the search functionality
            Index::store( $id, $html );
            // add the page to the sitemap output
            self::$sitemap->addPage($id);
            return true;
        }
        return false;
    }

    // getPage()
    // Initates a CURL session to grab the page using RETURNTRANSFER
    //
    static function getPage( $url ) {
        $url = trim(self::$root, '/') . '/' . trim($url, '/');
        $ch = curl_init();
        curl_setopt( $ch, CURLOPT_URL, $url );
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, TRUE);
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }

    static function getHREF( $s ) {
        $ignore = array( '/', '#' );
        $pattern = '#<a[^href]href=[\'"](.*?)[\'"]#i';
        preg_match_all( $pattern, $s, $matches );
        self::$urls = array_unique( array_merge(
                    self::$urls,
                    array_map(
                        function ($v) {
                        return str_replace( c::get('url') . '/', '', $v);
                        },
                        array_diff($matches[1], $ignore)
                        )
                    ));
        self::splitURLS( self::$urls );
    }

    // splitURLS()
    // stores the internal and external urls seperately
    //
    // @param   array  $urls The list of all urls captured for this page
    // @return  array  the list of internal urls
    static function splitURLS( $urls ) {
        $internal = array();
        $external = array();
        foreach( $urls as $u ) {
            // does it start with http(s)?
            if( substr( trim( $u ), 0, 5) == 'http:' ||
                    substr( trim( $u ), 0, 6) == 'https:' ||
                    substr( trim( $u ), 0, 4) == 'ftp:' ||
                    substr( trim( $u ), 0, 5) == 'ftps:' ) {
                $external[] = $u;
            } else {
                $internal[] = $u;
            }
        }
        self::$internal = array_unique( array_merge( self::$internal, $internal ));
        self::$external = array_unique( array_merge( self::$external, $external ));
    }

}

?>
