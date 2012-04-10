<?php
/**
 * BlogController.class.php
 */

class BlogController Extends LiriopeController {

  public function show( $vars=NULL ) {
    $blog = new Blogs();
    $blogs = $blog->setLimit(5)->get();

    $this->set( 'blogs', $blogs );
  }

}

?>


