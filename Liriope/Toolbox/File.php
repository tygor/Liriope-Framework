<?php

namespace Liriope\Toolbox;

use Liriope\c;
use Liriope\Toolbox\Directory;

//
// File.php
//

class File {
    public $path;
    public $fullpath;
    public $file;
    public $extension;
    public $modified;
    public $error = FALSE;

    //
    // __construct
    // initiates a file object
    //
    // @param  string  $path The URI path, or the full URI (autodetect $file)
    // @param  string  $file The file portion at the end of the URI
    // @return object  returns self for chaining
    //
    public function __construct( $path=NULL, $file=NULL ) {
        if( $path === NULL || $file === NULL ) return false;
        if( empty( $file )) {
            $this->autodetectPath( $path );
        } else {
            $this->setPath( $path );
            $this->setFile( $file );
        }
        if( $this->error ) trigger_error( "The file could not be properly initiated.", E_USER_ERROR );
        $this->fullpath = load::exists( $this->file, $this->path );
        $this->extension = $this->getExtension( $this->file );
    }

    // write()
    // Creates a new file
    // 
    // @param  string  $file The path for the new file
    // @param  mixed   $content Either a string or an array. Arrays will be converted to JSON. 
    // @param  bool    $append true: append the content to an exisiting file if available. false: overwrite. 
    // @return mixed   Returns the number of bytes written into the file, or FALSE on failure
    //   
    static function write($file,$content,$append=false){
        // If the $file variable contains directories, create them
        $info = pathinfo($file);
        if( $info['dirname'] ) {
            // Strip off any configuration "root" paths
            $newDir = str_replace(c::get('root.web') . '/', '', $info['dirname']);
            $newDir = c::get('root.web') . DIRECTORY_SEPARATOR . $newDir;
            Directory::make($newDir);
        }
        
        // Convert any content arrays to JSON encoded strings
        if( is_array( $content )) $content = json_encode( $content );

        $mode = ( $append ) ? FILE_APPEND : false;
        $write = file_put_contents( $file, $content, $mode );
        @chmod( $file, 0666 );
        return $write;
    }

    /** 
     * touch()
     * Creates an empty file
     *
     * @param     string  $file The path and file to create
     *
     * @return    mixed   Returns the number of bytes written into the file, or FALSE on failure
     */
    static function touch($file) {
        return self::write($file, '');
    }

  // read()
  // Reads the content of a file and returns it.
  // 
  // @param  string  $file The path for the file
  // @param  mixed   $parse The str object parse method to use
  // @return mixed 
  //   
  static function read( $file, $parse=FALSE ) {
    $content = new String(@file_get_contents($file));
    return ( $parse) ? $content->parse($parse) : $content->get();
  }

  // remove()
  // Deletes a file
  //
  // @param  string  $file The path for the file
  // @return boolean 
  //  
  static function remove($file) {
    return (file_exists($file) && is_file($file) && !empty($file)) ? @unlink($file) : false;
  }

  public function setPath( $v=NULL ) {
    if( $v === NULL ) {
      trigger_error( "No path was given", E_USER_WARNING );
      $this->error = TRUE;
    }
    $this->path = $v;
  }

  public function setFile( $v=NULL ) {
    if( $v === NULL ) {
      trigger_error( "No file was given", E_USER_WARNING );
      $this->error = TRUE;
    }
    $this->file = $v;
  }

  //
  // autodetectPath()
  // takes a fullpath string and tries to break off the file portion
  //
  public function autodetectPath( $v=NULL ) {
    if( $v === NULL ) {
      trigger_error( "No fullpath was given", E_USER_WARNING );
      $this->error = TRUE;
    }
    $pos = strrpos( $v, '/' );
    $file = substr( $v, ++$pos );
    $path = substr( $v, 0, --$pos );
    $this->path = $path;
    $this->file = $file;
  }

  public function getExtension( $file ) {
    return self::extension( $file );
  }

  public function __toString() {
    content::start();
    include($this->fullpath);
    return content::end( TRUE );
  }

  private function checkModified() {
    $time = filemtime( $this->fullpath );
    if( $time === false ) return false;
    $this->modified = date( "F d Y H:i:s", filemtime( $this->fullpath ));
    return true;
  }

  static function modified( $file ) {
    return filemtime( $file );
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
  
  static function extension( $f ) {
    $ext = str_replace( '.', '', strtolower( strrchr( trim( parse_url( $f, PHP_URL_PATH ) ), '.' )));
    return $ext;
  }

}
