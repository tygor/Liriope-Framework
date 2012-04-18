<?php
//
// Blogs.class.php
//

class Blogs {
  var $_handle;
  var $limit;
  var $path;
  var $entry = array();
  var $files = array();
  var $ignore = array();
  var $filter = array();

  public function __construct() {
    $this->_handle = NULL;
    $this->path = c::get( 'blog.dir', c::get( 'default.blog.dir' ));
    $this->filter = array(
      'php',
      'html',
      'txt'
    );
    $this->ignore = array(
      '.',
      '..',
      'index.php',
      'index.html'
    );
  }

  public function get( $limit=NULL ) {
    if( $limit !== NULL ) $this->setLimit = $limit;
    $this->readFiles()->sortFiles();
    closedir( $this->_handle );
    return $this->files;
  }

  public function setLimit( $num=5 ) {
    $this->limit = $num;
    return $this;
  }

  public function setFolder( $folder ) {
    if( $folder === NULL ) return false;
    $this->path = $folder;
    return $this;
  }

  private function startReading() {
    if( $handle = opendir( $this->path )) {
      $this->_handle = $handle;
    } else {
      $errormessage = "Uh oh! We couldn't read that folder."; 
      $this->entry = array( $errormessage );
      exit;
    }
  }

  private function readFiles() {
    if( $this->_handle === NULL ) $this->startReading();
    while( false !== ( $file = readdir( $this->_handle ))) {
      // limit to .php, .html, and .txt
      $info = pathinfo( $file );
      if( a::get( $info, 'extension', FALSE ) && !a::contains( $this->filter, a::get( $info, 'extension' ) )) continue;

      // skip if this is a directory
      $fullpath = c::get( 'root.web' ) . '/' .  $this->path . "/$file";
      if( is_dir( $fullpath )) continue;
// what if it's a directory?!
echo "<pre>";
echo "$file\n";

      // filter out files from the ignore list
      if( !a::contains( $this->ignore, $file )) {
echo "$file\n";
        $this->files[] = new Blogposts( $this->path, $file );
      }
echo "</pre>";
    }
    return $this;
  }

  private function sortFiles() {
    // for now, this will sort by modifiy date from most recent to latest
    $filesSorted = array();
    foreach( $this->files as $file ) {
      $filesSorted[$file->getModified()] = $file;
    }
    arsort( $filesSorted );
    $this->files = $filesSorted;
    return $this;
  }
}

