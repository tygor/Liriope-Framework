<?php

namespace Liriope\Toolbox;

//
// Directory object
// for handling directories
//

class Directory {
  //
  // Creates a new directory
  // 
  // @param   string  $dir The path for the new directory
  // @return  bool    FALSE on failure
  // 
  static function make( $dir, $mode=0755 ) {
    if( is_dir( $dir )) return TRUE;
    if( !@mkdir( $dir, $mode )) return FALSE;
    @chmod( $dir, $mode );
    return TRUE;
  }

  //
  // read()
  // reads the files within a directory
  //
  // @param  string  $dir The directory path to read
  // @retrun array   Returns an array of the contents
  //
  static function read( $dir ) {
    if( !is_dir( $dir )) return FALSE;
    $ignore = array( '.', '..', '.DS_Store' );
    $files = array_diff( scandir( $dir ), $ignore );
    foreach( $files as $k => $file ) {
      // ignore hidden files and folders
      if( substr( $file, 0, 1 ) === '.' ) unset( $files[$k] );
    }
    return $files;
  }

  //
  // modified()
  // recursive check for the latest modified time
  //
  // @param  string  $dir The path of the directory to look in
  // @param  int     $modified holds the last modified value
  static function modified( $dir, $modified = 0 ) {
    $files = self::read( $dir );
    foreach( (array) $files as $file ) {
      if( !is_dir( "$dir/$file" )) continue;
      $filectime = filemtime( "$dir/$file" );
      $modified = $filectime > $modified ? $filectime : $modified;
      // now go recursively
      $modified = self::modified( "$dir/$file", $modified );
    }
    return $modified;
  }

  //
  // contents()
  // Reads a directory and returns a list of the contents
  //
  // @param  string $dir The path to the directory
  // @return array  An array of files or an empty array
  static function contents( $dir ) {
    if( !is_dir( $dir )) return array();

    $files = self::read( $dir );
    $modified = filemtime( $dir );

    $data = array(
      'name'     => basename( $dir ),
      'root'     => $dir,
      'modified' => $modified,
      'files'    => array(),
      'children' => array()
    );

    foreach( $files as $file ) {
      if( is_dir( $dir . '/' . $file )) {
        $data['children'][] = $file;
      } else {
        $data['files'][] = $file;
      }
    }

    return $data;

  }

  // 
  // Deletes a directory
  // 
  // @param   string   $dir The path to the directory
  // @param   bool     $keep If set to TRUE the dir will be emptied, but kept
  // @return  bool     FALSE on failure
  //   
  static function remove($dir, $keep=FALSE) {
    if( !is_dir( $dir )) return FALSE;

    $handle = @opendir( $dir );
    $skip  = array('.', '..');

    if( !$handle ) return FALSE;

    while( $item = @readdir( $handle )) {
    if( is_dir( $dir . '/' . $item ) && !in_array( $item, $skip )) {
      self::remove( $dir . '/' . $item );
    } else if( !in_array( $item, $skip )) {
      @unlink( $dir . '/' . $item );
    }
  }

  @closedir($handle);
  if(!$keep) return @rmdir($dir);
  return true;

  }

  // 
  // Flushes a directory
  // 
  // @param   string   $dir The path of the directory
  // @return  bool     FALSE on failure
  //   
  static function clean( $dir ) {
    return self::remove( $dir, TRUE );
  }

}
