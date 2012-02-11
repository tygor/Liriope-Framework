<?php
/* --------------------------------------------------
 * FolderfileModel.class.php
 * --------------------------------------------------
 *
 */

// Direct access protection
if( !defined( 'LIRIOPE' )) die( 'Direct access is not allowed.' );

class FolderfileModel
{
  protected $root;
  protected $file;
  protected $folder;

  // __construct
  //
  public function __construct( $path, $root=NULL ) {
    $this->root = $root;
    $this->parsePath( $path );
  }

  // get
  //
  public function get( $dump=TRUE ) {
    $path = $this->root;
    if( !empty( $this->folder )) $path .= '/' . $this->folder;
    $path .= '/' . $this->file;

    if( !file_exists( $path )) return false;
    ob_start();
    require( $path );

    if( $dump ) {
      $content = ob_get_contents();
      ob_end_clean();
      return $content;
    }
    ob_end_flush();

  }

  // parsePath
  //
  public function parsePath( $path=NULL ) {
    if( empty( $path )) throw new Exception( __CLASS__ . ':' . __METHOD__ . ' requires a path in the arguments.' );
    // expect folder/file or simply folder/
    $path = ltrim( $path, '/' );
    $pathArray = explode( '/', $path );

    // if there is a file, it will be a the end of the array
    if( $this->fileExists( $path . '.php' )) {
      $file = end( $pathArray ) . '.php';
      array_pop( $pathArray );
      $path = implode( '/', $pathArray );
      $this->setFolder( $path );
      $this->setFile( $file );
      return true;
    }
    
    if( $this->folderExists( $path ) && $this->fileExists( $path . '/index.php' )) {
      $this->setFolder( $path );
      $this->setFile( 'index.php' );
      return true;
    }

    router::go( '/', 404 );
  }

  // setFolder
  //
  public function setFolder( $folder=NULL ) {
    if( empty( $folder )) throw new Exception( __CLASS__ . ':' . __METHOD__ . ' requires a folder name in the arguments.' );
    if( $this->folderExists( $folder )) $this->folder = $folder;
  }

  // setFile
  //
  public function setFile( $file=NULL ) {
    if( empty( $file )) throw new Exception( __CLASS__ . ':' . __METHOD__ . ' requires a file name in the arguments.' );
    if( $this->fileExists( $file )) $this->file = $file;
  }

  // folderExists
  //
  // checks for the reuqested folder
  private function folderExists( $folder ) {
    $path = $this->root . '/' . $folder;
    if( file_exists( $path ) && is_dir( $path )) return true;
    return false;
  }

  // fileExists
  //
  // checks for the reuqested file
  private function fileExists( $file ) {
    $path = $this->root;
    if( !empty( $this->folder )) $path .= '/' . $this->folder;
    $path .= '/' . $file;
    if( file_exists( $path ) && is_file( $path )) return true;
    return false;
  }
}
?>