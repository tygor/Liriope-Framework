<?php

namespace Liriope\Controllers;

use Liriope\Models\Yaml;
use Liriope\Toolbox\File;

//
// GalleryController.php
//

class GalleryController Extends LiriopeController {

  // show()
  // displays a list of the latest blog posts
  //
  public function show( $vars=NULL ) {
    $page = $this->getPage();

    $yaml = new Yaml( File::read( 'data/gallery.yaml' ));
    $gallery = $yaml->get();
    $images = $gallery->images;

    $page->set( 'gallery', $gallery );
    $page->set( 'images', $images );
  }

  // image()
  //
  public function image( $vars=NULL ) {
    $page = $this->getPage();

    $id = $vars[0];
    $yaml = new Yaml( File::read( 'data/gallery.yaml' ));
    $gallery = $yaml->get();

    $image = $gallery->images->$id;

    $page->set( 'image', $image );
  }

}
?>
