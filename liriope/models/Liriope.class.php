<?php
/* --------------------------------------------------
 * Liriope.class.php
 * --------------------------------------------------
 *
 */

// Direct access protection
if( !defined( 'LIRIOPE' )) die( 'Direct access is not allowed.' );

class Liriope {
  protected $root;
  protected $file;
  protected $folder;
  var $vars = array();

  // __construct
  //
  public function __construct( $path, $root=NULL ) {
    $this->root = $root;
    $this->parsePath( $path );
  }

  //
  // parsePath( $path )
  // breaks down the passed path from the controller via __construct() 
  // and stores the resulting folder and file for the future get() call
  //
  // (string) $path the partial path and file sans extension withing the web content root
  //
  public function parsePath( $path=NULL ) {
    if( $path===NULL ) throw new Exception( __CLASS__ . ':' . __METHOD__ . ' requires a path in the arguments.' );
    // expect /folder/file or simply /folder
    $path = ltrim( $path, '/' );
    $aryPath = explode( '/', $path );

    $file = end( $aryPath );
    array_pop( $aryPath );

    // assume the path is only a file (no folder) and check
    if( !$aryPath && $this->setFile( $file )) return true;

    // assume the path has a file and check
    if( $aryPath && $this->setFolder( implode( '/', $aryPath )) && $this->setFile( $file )) return true;

    // nope, so what about index?
    if( $this->setFolder( $path ) && $this->setFile( 'index.php' )) return true;

    // neither, so dump to 404
    // hijack the "content" and replace with 404 content
    $this->setFolder( c::get( 'default.404.folder' ));
    $this->setFile( c::get( 'default.404.file' ));
    return true;
  }

  // setFolder
  //
  public function setFolder( $folder=NULL ) {
    if( empty( $folder )) throw new Exception( __CLASS__ . ':' . __METHOD__ . ' requires a folder name in the arguments.' );
    if( !$this->folderExists( $folder )) return false;
    $this->folder = $folder;
    return true;
  }

  // setFile
  //
  public function setFile( $file=NULL ) {
    if( empty( $file )) throw new Exception( __CLASS__ . ':' . __METHOD__ . ' requires a file name in the arguments.' );
    $file = $this->fileExists( $file );
    if( $file === FALSE ) return false;
    $this->file = $file;
    return true;
  }

  // folderExists
  //
  // checks for the reuqested folder
  private function folderExists( $folder ) {
    $path = $this->root . '/' . $folder;
    if( file_exists( $path ) && is_dir( $path )) return true;
    return false;
  }

  //
  // fileExists( $file )
  // checks for the reuqested file
  //
  private function fileExists( $file ) {
    $path = $this->root;
    if( !empty( $this->folder )) $path .= '/' . $this->folder;

    // this will return the full path to the file, so in get() the folder is not needed
    if( $found = load::exists( $file, $path )) {
      return $found;
    }
    return false;
  }

  public function render( $page=NULL ) {
    content::start();
    include( $this->file );

    $render = content::end( TRUE );
    return $render;
  }

}

?>
