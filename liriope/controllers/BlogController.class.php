<?php
/**
 * BlogController.class.php
 */

// Direct access protection
if( !defined( 'LIRIOPE' )) die( 'Direct access is not allowed.' );

class BlogController Extends LiriopeController {

  // show()
  // displays a list of the latest blog posts
  //
  public function show( $vars=NULL ) {
    $blog = new Blogs();
    $blogs = $blog->setLimit(5)->setContext('intro')->getList();
    View::set( 'blogs', $blogs );
  }

  // post()
  // shows a single post by filename
  //
  public function post( $params=NULL ) {
    $post = new Blogs( c::get( 'blog.dir', c::get( 'default.blog.dir' )), $params[0] );
    View::set( 'content', $post->render() );
    // now that the post has been rendered to a string, the contained PHP will have been run
    $css = $post->get('stylesheets');
    $post->set( 'stylesheets', NULL );
    if( is_array( $css )) {
      foreach( $css as $c ) View::addStylesheet( $c['file'], $c['rel'] );
    }
    View::set( $post->get() );
  }

}

?>
