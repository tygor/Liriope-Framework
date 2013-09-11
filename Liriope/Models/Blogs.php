<?php

namespace Liriope\Models;

use Liriope\Models\Obj;
use Liriope\Component\Content\Buffer;
use Liriope\Toolbox\String;
use Liriope\Toolbox\a;
use Liriope\Toolbox\Directory;

class Blogs extends Obj {
  // the name of the blog folder
  var $name;
  // the file system path to the blog's parent folder
  var $root;
  // the path between the root content and the blog
  var $parent;
  // the timestamp of the last modified file
  var $modified;
  // the files within the blog
  var $files = array();
  // holds the initilization status of the blog files
  var $initFlag = FALSE;

  // __construct()
  // builds the Blog model object
  //
  // @param  string  $path The path to where the blog file is
  // @param  string  $file The file that contains the blog content
  // all params are optional
  //
  public function __construct( $path=NULL, $file=NULL) {
    $data = Directory::contents( $path );
    $this->name = $data['name'];
    $this->root = rtrim( $data['root'], '/'.$data['name']);
    $parent = new String($this->root);
    $this->parent = $parent->replace(\c::get('root.content'),'')->get();
    $this->modified = $data['modified'];
    // now store recursively from the blog root
    $this->storeBlogArticles( $this->root .'/'. $this->name );
  }

  private function storeBlogArticles( $folder ) {
    $data = Directory::contents( $folder );
    foreach( $data['files'] as $k => $file ) {
      // only grab .php files
      if( pathinfo( $folder . '/' . $file, PATHINFO_EXTENSION ) !== 'php' ) continue;
      $this->files[] = $folder . '/' . $file;
    }
    foreach( $data['children'] as $subfolder ) {
      $this->storeBlogArticles( $folder . '/' . $subfolder );
    }
  }

  // init()
  // Reads the blog file, grabs it's settings and contents and
  // stores them to the new object
  //
  private function init( $file ) {
    if( empty( $file )) trigger_error( 'The passed file is empty', E_USER_ERROR );

    // the pseudo-page object to act as surrogate page for the post to load into
    $page = new Obj();

    // grab the content with an output buffer
    Buffer::start();
    include( $file );
    $page->content = Buffer::end( TRUE );

    // force a page title from the first <h*> tag
    if( !$page->title() ) {
      $pattern = '/<h[1-6][^>]*>([^<]*)<\/h[1-6]>/i';
      if( preg_match( $pattern, $page->content(), $matches )) $page->title = $matches[1];
      else $page->title = 'Untitled';
    }

    // and set some info about each post
    $info = pathinfo( $file );
    $dir = new String($info['dirname']);
    $page->dir = $dir->minus(\c::get('root.content').'/')->get();
    $page->file = $info['basename'];
    $url = new String($info['filename']);
    $page->url = ($url->to_lowercase()->get() === 'index') ? $page->dir : $page->dir . '/' . $info['filename'];
    $page->date = ($page->date()===NULL) ? date( 'Y-m-d H:i:s', filemtime($file)) : $page->date();
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

  // linkH1()
  // returns the content with the <h1> content wrapped in an anchor
  //
  static function linkH1( $content, $url ) {
    // wrap the <h1> tag in an anchor?
    if( \c::get( 'blog.link.title', TRUE )) {
      $pattern = '/(<h1[^>]*>)([^<]*)(<\/h1>)/i';
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
    $showIntro = \c::get( 'blog.intro.show', FALSE );
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
  public function getList( $limit, $page=1 ) {
    $this->initAll();
    $start = ( $page * $limit ) - $limit;
    $entries = array_slice( $this->files, $start, $limit);
    return (array) $entries;
  }

  // initAll()
  // loads each blog file so that the file's settings can be read
  //
  public function initAll() {
    if( $this->initFlag ) return TRUE;
    // convert each blog file into an object
    $files = array();
    foreach( $this->files as $file ) {
      $files[] = $this->init( $file );
    }
    $this->files = $files;
    $this->sortFiles();
    $this->initFlag = TRUE;
    return $this;
  }

  // getNext()
  // returns the current() blog post and moves the array position
  //
  public function getNext() {
    $this->initAll();
    $post = current($this->files);
    if(next($this->files) === false) {
        return $post;
    }
    return $post;
  }

  // getPost()
  // returns the blog by filename
  //
  // @param  string  $file The file of the blog to get
  // @return object  The blog object
  public function getPost( $file ) {
    // if the passed $file is a file, then go with it
    if( !is_file($this->root.'/'.$this->name.'/'.$file.'.php')) {
      // if the passed $file is a directory, then look inside for index.php
      if( !is_dir( $this->root .'/'. $this->name .'/'. $file )
        && !is_file( $this->root.'/'.$this->name.'/'.$file)) {
        go(404);
        trigger_error('The reference to that post is wrong.', E_USER_ERROR );
      } else {
        $file = $file . '/index';
      }
    }

    // check for a file first then check for a directory with index.php inside
    $file = a::first(a::search($this->files, $file));
    $post = $this->init( $file );
    if( $post ) return $post;
    return FALSE;
  }

  // sortFiles
  // sorts the blog files by their internal $date value
  // or the fallback filemtime() value
  //
  // @param  string  $mode The way in which to sort these files (ASC, DESC)
  //
  private function sortFiles( $mode='DESC' ) {
    // if it doesn't have a $date set, add a filemtime to the object
    foreach( $this->files as $file ) {
      if( $file->date()===NULL ) {
        $modified = filemtime( $this->root . '/' . $file->file() );
        $file->date = date( 'Y-m-d H:i:s', $modified );
      }
    }
    uasort( $this->files, "self::compareModifiedDate" );
    reset( $this->files );
    return $this;
  }

  private static function compareModifiedDate( $a, $b ) {
    $am = strtotime($a->date());
    $bm = strtotime($b->date());
    if( $am == $bm ) return 0;
    return( $am < $bm ) ? +1 : -1;
  }

  static function findReadmore( $content ) {
    // now, find the offset of where that class is
    $pattern = '/<[^>]*' . \c::get( 'readmore.class', \c::get( 'default.readmore.class', 'readmore' ) ) . '[^>]*>/';
    $count = preg_match( $pattern, $content, $matches, PREG_OFFSET_CAPTURE );

    if( $count !== 1 ) return false;
    return $matches[0][1];
  }

}
?>
