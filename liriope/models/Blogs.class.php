<?php
//
// Blogs.class.php
//

// Direct access protection
if( !defined( 'LIRIOPE' )) die( 'Direct access is not allowed.' );

class Blogs extends Page {
  var $limit;
  var $path;
  var $entry = array();
  var $context;
  var $content;
  var $file;
  var $files = array();
  var $ignore = array();
  var $filter = array();

  // __construct()
  // builds the Blog model object
  //
  // @param  string  $path The path to where the blog file is
  // @param  string  $file The file that contains the blog content
  // all params are optional
  //
  public function __construct( $path=NULL, $file=NULL) {
    // on construct, this object can be either an actual post, or an object of multiple posts.
    $this->path = ( $path === NULL ) ? c::get( 'blog.dir', c::get( 'default.blog.dir' )) : $path;
    $this->file = $file;
    $this->fullpath = load::exists( $file, $this->path );
    $this->context = 'show';
    $this->filter = array( 'php', 'html', 'txt');
    $this->ignore = array( '.', '..', 'index.php', 'index.html');
    // for blog posts, we need to store the blog content now, during construct
    // this way, the internal PHP logic is also accomplished before the controller
    // does anything else. We determine that this instance of Blogs is an actual
    // post by checking the filepath.
    if( is_file( c::get( 'root.web' ) . '/' . $this->fullpath )) {
      // TODO: the rendereding calls into effect any variable within, and for the "show" action _
      // this is undesirable since they aren't yet set and won't be to simply list. _
      // instead, I need a different "render" for the list context.
      $this->content = $this->render();
    }
  }

  // __toString()
  // return a portion of the full content based on the context
  //
  public function __toString() {
    if( $this->context === 'list' ) {
      return $this->getIntro();
    } else {
      return $this->getArticle();
    }
  }

  // render()
  // converts the Blogs path and file into a read string
  //
  // @return string The file of this blog, buffered, and returned as a string
  //
  public function render() {
    // start output buffering and grab the full post file
    content::start();
    // I need a $page object so that the content included can act normally
    if( $this->context = "list" ) $page = new Page();
    $blog =& $this;
    include( $this->fullpath );
    $post = content::end( TRUE );

    return $post;
  }

  // getIntro()
  // returns the post content up to the readmore link
  //
  public function getIntro() {
    // chop all but the intro text
    if( $matchOffset = $this->findReadmore()) $post = substr( $this->content, 0, $matchOffset );

    // is the post empty because there is no readmore?
    if( empty( $post )) $post = $this->content;

    // wrap the <h1> tag in an anchor?
    if( c::get( 'blog.link.title', TRUE )) {
      $pattern = '/(<h1[^>]*>)([a-z0-9 ]*)(<\/h1>)/i';
      $replacement = '$1<a href="' . $this->getLink() . '">$2</a>$3';
      $post = preg_replace( $pattern, $replacement, $post );
    }

    return $post;
  }

  // getArticle()
  // returns the post content after the readmore link
  //
  public function getArticle() {
    // show the intro text in the full post?
    $intro = c::get( 'blog.intro.show', FALSE );
    $post = $this->content;

    // chop off the intro text portion
    if( !$intro && $matchOffset = $this->findReadmore( $post )) {
      $post = substr( $post, $matchOffset );
      $post = substr( $post, strpos( $post, '>' )+1 );
    }
    $post = trim( $post );

    return $post;
  }

  // getList()
  // lists the last posts
  //
  public function getList( $limit=NULL ) {
    $this->setLimit = $limit;
    $this->files = dir::read( $this->path );
    $this->filterFiles()->sortFiles()->limitFiles();
    $entries = array();
    foreach( $this->files as $k => $f ) {
      $entries[$k] = new Blogs( $this->path, $f );
      $entries[$k]->setContext('list');
    }
    return (array) $entries;
  }

  public function setContext( $name=NULL ) {
    if( $name === NULL ) return $this;
    // this will help to determine what to use when
    // the __toString function is called.
    $this->context = $name;
    return $this;
  }

  public function setLimit( $num=5 ) {
    $this->limit = $num;
    return $this;
  }

  public function getLimit() {
    return $this->limit;
  }

  public function setFolder( $folder ) {
    if( $folder === NULL ) return false;
    $this->path = $folder;
    return $this;
  }

  private function filterFiles() {
    // ignore certain files
    $this->files = array_diff( $this->files, $this->ignore );
    foreach( $this->files as $k => $f ) {
      if( is_dir( $this->path . '/' . $f )) {
        unset( $this->files[$k] );
      }
      // limit blog files to certain extensions
      if( !array_search( Files::extension( $f ), $this->filter )) continue;
    }
    return $this;
  }

  private function sortFiles() {
    // for now, this will sort by modifiy date from most recent to latest
    $check = array();
    foreach( $this->files as $k => $f ) $check[$k] = $this->path . '/' . $f;
    uasort( $check, "self::compareModifiedDate" );
    $sorted = array();
    foreach( $check as $k => $f ) $sorted[$k] = $this->files[$k];
    $this->files = $sorted;
    return $this;
  }

  private static function compareModifiedDate( $a, $b ) {
    $am = filemtime( $a );
    $bm = filemtime( $b );
    if( $am == $bm ) return 0;
    return( $am < $bm ) ? +1 : -1;
  }

  private function limitFiles() {
    $l = $this->getLimit();
    $this->files = array_slice( $this->files, 0, $l );
    return $this;
  }

  private function findReadmore() {
    // now, find the offset of where that class is
    $pattern = '/<[^>]*' . c::get( 'readmore.class', c::get( 'default.readmore.class', 'readmore' ) ) . '[^>]*>/';
    $count = preg_match( $pattern, $this->content, $matches, PREG_OFFSET_CAPTURE );

    if( $count !== 1 ) return false;
    return $matches[0][1];
  }

  public function getLink() {
    $info = pathinfo( $this->file );
    return 'blog/' . $info['filename'];
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
    $pubdate = $this->date;
    if( $pubdate===NULL ) return $this->getModified();
    return strtotime( $pubdate );
  }

}

