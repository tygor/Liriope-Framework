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
    global $page;

    $base = isset( $vars['dir'] ) ? $vars['dir'] : c::get( 'blog.dir' );
    $dir = c::get( 'root.content' ) . '/' . $base;
    if( isset( $vars['dir'] )) $this->useView( basename( $dir ));

    $blogs = new Blogs( $dir );
    $posts = $blogs->getList( a::get( $vars, 'limit', 10 ), a::get( $vars, 'page', 1 ));

    // catch an empty set
    if( empty( $posts )) {
      $page->set( 'error', TRUE );
      return;
    }

    $page->set( 'blogs', $posts );
    $page->set( 'limitNum', a::get( $vars, 'limit', 10 ));
    $page->set( 'totalPages', ceil( $blogs->count() / a::get( $vars, 'limit', 10 )));
    $page->set( 'pageNum', a::get( $vars, 'page', 1 ));
  }

  // post()
  // shows a single post by filename
  //
  public function post( $vars=NULL ) {
    global $page;

    $base = isset( $vars['dir'] ) ? $vars['dir'] : c::get( 'blog.dir' );
    $dir = c::get( 'root.content' ) . '/' . $base;
    if( isset( $vars['dir'] )) $this->useView( basename( $dir ));

    $blogs = new Blogs( $dir );
    $post = $blogs->getPost( $vars[0] );

    $page->set( 'post', $post );

    // get the post css, js, and script blocks
    foreach( (array) $post->css as $v ) $page->css($v);
    foreach( (array) $post->js as $v ) $page->js($v);
    foreach( (array) $post->script as $v ) $page->script($v);
  }

  // feed()
  //
  public function feed( $vars=NULL ) {
    global $page;

    $base = isset( $vars['dir'] ) ? $vars['dir'] : c::get( 'blog.dir' );
    $dir = c::get( 'root.content' ) . '/' . $base;
    if( isset( $vars['dir'] )) $this->useView( basename( $dir ));

    $blogs = new Blogs( $dir );
    $posts = $blogs->getList( a::get( $vars, 'limit', 10 ), a::get( $vars, 'page', 1 ));

    foreach( $posts as $post ) {
      $post->description = $post->intro();
    }

    $page->theme = 'feed';
    $page->set( 'items',  $posts);
  }

}

?>
