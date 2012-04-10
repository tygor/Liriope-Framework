<?php

class Blogs {
  var $_handle;
  var $limit;
  var $entry = array();
  var $ignoreFiles = array();

  public function __construct() {
    $this->_handle = NULL;
    $this->path = 'content/blog';
    $this->ignoreFiles = array(
      '.',
      '..',
      'index.php',
      'index.html'
    );
  }

  public function get( $limit=NULL ) {
    if( $limit !== NULL ) $this->setLimit = $limit;
    $return = $this->readFiles();
    closedir( $this->_handle );
    return $return;
  }

  public function setLimit( $num=5 ) {
    $this->limit = $num;
    return $this;
  }

  private function startReading() {
    if( $handle = opendir( $this->path )) {
      $this->_handle = $handle;
    } else {
      die( "Uh oh! We couldn't read that folder" );
    }
  }

  private function readFiles() {
    if( $this->_handle === NULL ) $this->startReading();
    while( false !== ( $entry = readdir( $this->_handle ))) {
      if( !in_array( $entry, $this->ignoreFiles )) {
        $this->entry[] = $entry;
      }
    }
    return $this->entry;
  }

}
