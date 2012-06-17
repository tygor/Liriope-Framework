<?php
/**
 * GalleryController.class.php
 */

// Direct access protection
if( !defined( 'LIRIOPE' )) die( 'Direct access is not allowed.' );

class GalleryController Extends LiriopeController {

  // show()
  // displays a list of the latest blog posts
  //
  public function show( $vars=NULL ) {
    global $page;

    $yaml = new Yaml( f::read( 'data/gallery.yaml' ));
    $gallery = $yaml->get();
    $images = $gallery->images;

    $page->set( 'gallery', $gallery );
    $page->set( 'images', $images );
  }

  // image()
  //
  public function image( $vars=NULL ) {
    global $page;

    $id = $vars[0];
    $yaml = new Yaml( f::read( 'data/gallery.yaml' ));
    $gallery = $yaml->get();

    $image = $gallery['images'][$id];

    $page->set( 'image', $image );
  }

}
?>
