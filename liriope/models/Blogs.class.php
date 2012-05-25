<?php
//
// Blogs.class.php
//

// Direct access protection
if( !defined( 'LIRIOPE' )) die( 'Direct access is not allowed.' );

class Blogs extends obj {
  var $name;
  var $root;
  var $modified;
  var $files = array();
  var $children = array();

  // __construct()
  // builds the Blog model object
  //
  // @param  string  $path The path to where the blog file is
  // @param  string  $file The file that contains the blog content
  // all params are optional
  //
  public function __construct( $path=NULL, $file=NULL) {
    $data = dir::contents( $path );
    foreach( $data as $k => $v ) $this->$k = $v;
  }

  // objectify()
  // Turns a list of file strings into objects so that we can read
  // the overloaded variables from the blog content
  //
  // @param  string  $file Optional file to turn into an object
  //
  private function objectify() {
    $files = array();
    foreach( $this->files as $file ) {
      $files[] = $this->init( $file );
    }
    $this->files = $files;
    return TRUE;
  }

  private function init( $file ) {
    $page = new obj();

    // grab the content with an output buffer
    content::start();
    include( $this->root . '/' . $file );
    $page->content = content::end( TRUE );

    // force a page title from the first <h1> tag if none exists
    if( !$page->title() ) {
      $pattern = '/(<h1[^>]*>)([a-z0-9\'\".!? ]*)(<\/h1>)/i';
      if( preg_match( $pattern, $page->content(), $matches )) $page->title = $matches[0];
    }

    // and set some info about each post
    $page->file = $file;
    $info = pathinfo( $file );
    $page->url = $this->name . '/' . $info['filename'];
    if( !isset( $page->date )) {
      $modified = filemtime( $this->root . '/' . $file );
      $page->date = date( 'Y-m-d H:i:s', $modified );
    }
    $page->time = strtotime( $page->date );

    // get post parts
    $parts = $this->parse( $page );
    $page->intro = $parts['intro'];
    $page->article = $parts['article'];

    return $page;
  }

  // count()
  // Counts the number of blog files
  //
  function count() {
    return count( $this->files );
  }

  // render()
  // converts the Blogs path and file into a read string
  //
  // @return string The file of this blog, buffered, and returned as a string
  //
  public function render() {
    global $page;

    // start output buffering and grab the full post file
    content::start();
    $blog =& $this;
    include( $this->fullpath );
    $post = content::end( TRUE );

    return $post;
  }

  // linkH1()
  // returns the content with the <h1> content wrapped in an anchor
  //
  static function linkH1( $content, $url ) {
    // wrap the <h1> tag in an anchor?
    if( c::get( 'blog.link.title', TRUE )) {
      $pattern = '/(<h1[^>]*>)([a-z0-9\'\".!? ]*)(<\/h1>)/i';
      $replacement = '$1<a href="' . url( $url ) . '">$2</a>$3';
      $content = preg_replace( $pattern, $replacement, $content );
    }
    return $content;
  }

  // parse()
  // returns the two main blog parts, Intro and Article
  //
  // @param  obj   $page The blog object to use
  // @return array An array with the two parts as HTML
  private function parse( $page ) {
    $post = $page->content;

    // if there is no readmore, then return the full content
    if( !$offset = self::findReadmore( $post )) {
      return array( 'intro' => $post, 'article' => $post );
    }

    // INTRO
    $intro = substr( $post, 0, $offset );
    $intro = trim( self::linkH1( $intro, $page->url() ));

    // ARTICLE
    $showIntro = c::get( 'blog.intro.show', FALSE );
    if( !$showIntro ) {
      $post = substr( $post, $offset );
      $post = substr( $post, strpos( $post, '>' ) + 1 );
    }
    $article = trim( $post );

    return array( 'intro' => $intro, 'article' => $article );
  }

  // getList()
  // lists the latest posts
  //
  // @param  int   $limit The amount of blogs entries to return
  // @param  int   $page  The starting multiplier of $limit
  // @return array An array of entires
  //
  public function getList( $limit, $page ) {
    $this->objectify();
    $this->sortFiles();
    $start = ( $page * $limit ) - $limit;
    $entries = array_slice( $this->files, $start, $limit);
    return (array) $entries;
  }

  // getPost()
  // returns the blog by filename
  //
  // @param  string  $file The file of the blog to get
  // @return object  The blog object
  public function getPost( $file ) {
    $info = pathinfo( load::exists($file, $this->root));
    $file = $info['basename'];
    $post = $this->init( $file );
    if( $post ) return $post;
    return FALSE;
  }

  // sortFiles
  // sorts the blog files by their internal $date value
  // or the fallback filemtime() value
  //
  private function sortFiles() {
    // if it doesn't have a $date set, add a filemtime to the object
    foreach( $this->files as $file ) {
      if( !isset( $file->date )) {
        $modified = filemtime( $this->root . '/' . $file->file() );
        $file->date = date( 'Y-m-d H:i:s', $modified );
      }
    }
    uasort( $this->files, "self::compareModifiedDate" );
    return $this;
  }

  private static function compareModifiedDate( $a, $b ) {
    $am = $a->date();
    $bm = $b->date();
    if( $am == $bm ) return 0;
    return( $am < $bm ) ? +1 : -1;
  }

  static function findReadmore( $content ) {
    // now, find the offset of where that class is
    $pattern = '/<[^>]*' . c::get( 'readmore.class', c::get( 'default.readmore.class', 'readmore' ) ) . '[^>]*>/';
    $count = preg_match( $pattern, $content, $matches, PREG_OFFSET_CAPTURE );

    if( $count !== 1 ) return false;
    return $matches[0][1];
  }

  private function checkModified() {
    $time = filemtime( $this->fullpath );
    if( $time === FALSE ) trigger_error( 'Could not get the file\'s modified time', E_USER_ERROR );
    $this->modified = $time;
    return true;
  }

  // getModified()
  // returns the file's mtime or the assigned pubDate
  //
  public function getModified() {
    if( !isset( $this->modified )) {
      $this->checkModified(); 
    }
    return $this->modified;
  }

  // getPubDate()
  // returns the assigned pubDate is one was set
  //
  public function getPubDate() {
    if( $this->pubdate ) return $this->pubdate;
    $pubdate = $this->date;
    if( $pubdate===NULL ) return $this->getModified();
    return strtotime( $pubdate );
  }

}

