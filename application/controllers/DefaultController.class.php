<?php
/**
 * DefaultController.class.php
 */

class DefaultController Extends LiriopeController {

  public function show( $getVars=NULL ) {
  }

  public function filepage( $getVars=NULL ) {
    if( empty( $getVars )) { // call folder=home file=index
      $path = '/home/index';
    } else {
      $path = '/' . implode( '/', $getVars );
    }
    $content = new FolderfileModel( $path, c::get( 'root.content' ));
echo "<pre>";
var_dump($content);
echo "</pre>";

    $this->set( 'content', $content->get() );
  }

}

?>

