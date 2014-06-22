<?php

namespace Liriope\Component\Search;

use Liriope\Toolbox\a;
use Liriope\Component\c;
use Liriope\Toolbox\String;
use Liriope\Toolbox\File;

class index {

  // an array of urls to ignore
  static $ignoreURLs = array( 'search' );

  // an array of all found URLs
  static $urls = array();
  // an array of saved URLs to crawl for indexing
  static $internal = array();
  // a list of external links
  static $external = array();

  // the raw input html for the whole page
  static $html;
  // the raw html for just the page body (sans-template)
  static $body;

  // the variable to hold our cleaned content strings in an array
  static $content = array();

  // the word count array
  static $tally = array();

  // the named meta tags to keep
  static $metaNames = array( 'description', 'author', 'keywords' );
  // the words to ignore
  static $ignore = array( 'i', 'a', 'an', 'and', 'the', 'some', 'to', 'for', 'that', 'it', 'is', 'of', 'so', 's' );

  // pattern for stripping out unwanted characters to index
  static $stripPattern = '/[^a-z0-9\'"=:%]/iu';

  // multiplier for title and meta tag words, giving them more importance
  static $multiplier;

  /**
   * store()
   * 
   * Stores the passed HTML page in an index file. This index contains
   * every word not in the ignore array, and it's associated count from
   * this page.
   *
   * @param  string  $id   The page URI after the domain name
   * @param  string  $html The page content to index
   * @param  string  $body (optional) Just the body of the page, ignoring the theme frame
   */
  static function store( $id, $html, $body=NULL ) {
    if( a::contains( self::$ignoreURLs, $id )) return false;
    self::$body = $body!==NULL ? $body : $html; // to index for content
    self::$html = $html; // for grabbing crawler links, title and meta

    // grab the multiplier from the config settings
    // this assigned a weighted value to special words like meta info and title tag text
    self::$multiplier = c::get('index.multiplier', 3);

    // first, process what we want from the full HTML
    self::saveLinks();
    $meta = self::findMeta();
    $title = self::findTitle();

    // then index the content section of the page body (sans-template wrapper)
    $img = self::findImageText();
    self::removeNonContentTags();
    self::removeHtmlComments();
    self::$body = strip_tags( self::$body );
    $naked = html_entity_decode(preg_replace("/\s+/", " ", self::$body), ENT_QUOTES | ENT_HTML5, 'UTF-8');
    $words = explode(' ', trim( self::stripToWords( self::$body ), ' ,' ));

    // get the words from the title and meta tags, multiplying their score by duplicating their words
    $goldwords = array();
    foreach( $meta as $m ) {
      end($goldwords);
      $goldwords += array_fill( key($goldwords)+1, self::$multiplier, $m );
    }
    foreach( explode(',',trim(self::stripToWords($title))) as $t ) {
      end($goldwords);
      $goldwords += array_fill( key($goldwords)+1, self::$multiplier, $t );
    }
    // combine our word results
    self::$content = array_filter(a::combine( $goldwords, $words, $img ));

    // now tally what we got
    self::countWords();

    $store = array(
      $id => array(
        'title' => $title,
        'meta' => $meta,
        'content' => $naked,
        'index' => self::$tally
      )
    );
    $dir = c::get( 'root.index', c::get( 'root.web' ) . '/index' );
    $file = $dir . '/' . self::prep( $id ) . '.txt';

    File::write( $file, $store );
  }

  // unstore()
  // remove a page from the index
  //
  // @param  string  $uri the sought uri that needs to be removed
  //
  static function unstore( $uri ) {
    $dir = c::get( 'root.index', c::get( 'root.web' ) . '/index' );
    $uri = new String($uri);
    $file = $dir . '/' . $uri->replace( '/', '|' ) . '.txt';
    $success = File::remove($file);
    // TODO: Remove this link from the sitemap.xml too. Create/call a sitemap model to do this task.
  }

  // stripToWords()
  // removes everything except letters and numbers and a colon (:)
  // but leaves the words separated by a space
  //
  static function stripToWords( $content ) {
    // first pass
    $content = preg_replace( self::$stripPattern, ' ', $content );
    // second pass
    $content = preg_replace( '/[\s]+|:\s/', ' ', $content );
    return $content;
  }

  // removeNonContentTags()
  // strips out <head>, <script> and <style> tags inlcuding their contents
  //
  static function removeNonContentTags() {
    $content = self::$body;
    $content = preg_replace( '/<\s*script.*>.*<\/script>/imsxU', '', $content );
    $content = preg_replace( '/<\s*style.*>.*<\/style>/imsxU', '', $content );
    $content = preg_replace( '/<\s*head[^>]*.*<\/head>/imsxU', '', $content );
    self::$body = $content;
  }

  // removeHtmlComments()
  // strips out HTML comments
  static function removeHtmlComments() {
    $content = preg_replace( '/<!--.*-->/imsxU', '', self::$body );
    self::$body = $content;
  }

  // saveLinks()
  // save the anchor tags' href locations for crawling
  //
  static function saveLinks() {
    $pattern = "/<a.*href=['\"](.*?)['\"].*>.*<\/a>/i";
    preg_match_all( $pattern, self::$html, self::$urls );
    self::organizeLinks();
  }

  // organizeLinks()
  // traverse the found links and extract the internal links
  //
  static function organizeLinks() {
    if( empty( self::$urls ) || !isset( self::$urls[1] )) return FALSE;
    $array = self::$urls[1];
    $origin = c::get( 'url' );
    $result = array();
    $c = count( $array );
    for( $i=0; $i<$c; $i++ ) {
      if( !empty( $array[$i] )) {
        if( strpos( $array[$i], '://', 0 ) === FALSE ||
            substr( $array[$i], 0, strlen( $origin )) === $origin ) {
          if( $array[$i] !== '#' ) {
            $result[] = $array[$i];
          }
        }
      }
    }
    self::$internal = $result;
    self::$external = array_diff( self::$urls[1], $result );
  }

  // findImageText()
  // seeks and returns content from title and alt attributes
  //
  static function findImageText() {
    $title = array();
    $pattern = "/title=['\"]([^'\"]*)['\"]*+/i";
    preg_match_all( $pattern, self::$body, $title );

    $alt = array();
    $pattern = "/alt=['\"]([^'\"]*)['\"]*+/i";
    preg_match_all( $pattern, self::$body, $alt );

    $titlewords = implode( ' ', $title[1] );
    $altwords = implode( ' ', $alt[1] );
    return explode( ' ', trim( $titlewords . ' ' . $altwords ));
  }

  // findMeta()
  // seeks and returns the contents of named meta tags from the head
  //
  static function findMeta() {
    $found = array();
    $pattern = '/<meta.*name=[\'"]([a-z0-9 ]*)[\'"].*>/i';
    preg_match_all( $pattern, self::$html, $found );
    $return = array();
    for( $c=0; $c<count($found[0]); $c++ ) {
      if( in_array( $found[1][$c], self::$metaNames )) {
        $pattern = '/content=[\'"](.*)[\'"]/i';
        preg_match_all( $pattern, $found[0][$c], $content );
        $return = a::trim( a::combine( $return, explode( ' ', self::stripToWords( $content[1][0] ))), ' .' );
      }
    }
    return $return;
  }

  // findTitle()
  // returns the contents of the title tag
  //
  static function findTitle() {
    $pattern = '/<\s*title[^>]*>(.*)<\/title>/i';
    preg_match( $pattern, self::$html, $title );
    if( !isset( $title[1] )) return FALSE;
    return $title[1];
  }

  // countWords()
  // counts the instances of each word and returns an array of word => count
  //
  static function countWords( $array=NULL ) {
    if( $array === NULL ) $array = self::$content;
    $tally = array();
    foreach( $array as $word ) {
      $word = new String($word);
      $word_id = $word->to_lowercase()->trim(' .,-"\'')->get();
      if( !isset( $tally[$word_id] )) $tally[$word_id] = 1;
      else $tally[$word_id] = $tally[$word_id] + 1;
    }
    $tally = array_diff_key( $tally, array_flip( self::$ignore ));
    arsort( $tally );
    self::$tally = $tally;
  }

  // prep()
  // prepares the string for writing a file
  //
  static function prep( $string ) {
    return str_replace( '/', '|', $string );
  }

} ?>
