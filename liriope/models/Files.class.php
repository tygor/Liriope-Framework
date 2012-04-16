<?php
//
// Files.class.php
//

class Files {
  public $path;
  public $fullpath;
  public $file;
  public $modified;

  public function __construct( $path=NULL, $file=NULL ) {
    if( $path === NULL || $file === NULL ) return false;
    $this->path = $path;
    $this->file = $file;
    $this->fullpath = load::exists( $file, $path );
  }

  public function __toString() {
    ob_start();
    include($this->fullpath);
    $return = ob_get_contents();
    ob_end_clean();
    return $return;
  }

  private function checkModified() {
    $time = filemtime( $this->fullpath );
    if( $time === false ) return false;
    $this->modified = date( "F d Y H:i:s", filemtime( $this->fullpath ));
    return true;
  }

  public function getModified() {
    if( $this->modified === NULL ) {
      $this->checkModified();
    }
    return $this->modified;
  }

  public function getPath() {
    // for easy use, this is actually getFullPath, returning
    // the path plus the file name.
    return $this->fullpath;
  }

  public function __destruct() {
  }

}

?>
