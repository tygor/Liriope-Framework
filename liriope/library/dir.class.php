<?php
//
// Directory object
// dir.class.php
// for handling directories
//

// Direct access protection
if( !defined( 'LIRIOPE' )) die( 'Direct access is not allowed.' );

class dir {
  //
  // read()
  // reads the files within a directory
  //
  // @param  string  $dir The directory path to read
  // @retrun array   Returns an array of the contents
  //
  static function read( $dir ) {
    if( !is_dir( $dir )) return false;
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
    foreach( $files as $f ) {
      // skip files
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

    $files = dir::read( $dir );
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

}

?>
