<?php
//
// Blogs.class.php
//

// Direct access protection
if( !defined( 'LIRIOPE' )) die( 'Direct access is not allowed.' );

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
    $this->setLimit = $limit;
    $this->files = dir::read( $this->path );
    $this->filterFiles()->sortFiles()->limitFiles();
    $entries = array();
    foreach( $this->files as $f ) $entries[] = new Blogposts( $this->path, $f );
    return $entries;
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
    // TODO: this is hairy, but works:
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
}

