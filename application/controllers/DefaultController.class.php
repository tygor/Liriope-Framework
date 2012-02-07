<?php
/**
 * DefaultController.class.php
 */

class DefaultController Extends LiriopeController {

  public function show( $getVars=NULL ) {
    theme::addStylesheet( '../plugins/orbit/orbit-1.3.0.css' );
  }

  public function page( $getVars=NULL ) {
    // expected $getVars are either "folder" => $val, "file" => $val
    // or $folder => $file
    // I haven't decided how to do this yet, but a pretty URI would be
    // http://somesite.com/folder <-- calling the default index.php file
    // -OR-
    // http://somesite.com/folder/file
    
    // homepage    = array( - )
    // folder only = array( [0] => $folder )
    // folder file = array( [$folder] => $file )

    if( empty( $getVars )) { // call folder=home file=index
      $folder = 'home';
      $file = 'index';
    } else {
      $key = key( $getVars );
      if( $key===0 ) {
        // we're getting index.php from the folder current($getVars)
        $folder = current( $getVars );
        $file = 'index';
      } else {
        $folder = key( $getVars );
        $file = current( $getVars );
      }
    }
    $content = new FolderfileModel( $folder, $file, c::get( 'root.content' ));
    $this->set( 'content', $content->get() );
  }

}

?>

