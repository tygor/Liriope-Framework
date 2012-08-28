<?php
//
// BlogController.class.php
//

// Direct access protection
if( !defined( 'LIRIOPE' )) die( 'Direct access is not allowed.' );

class BlogController Extends LiriopeController {

  // show()
  // displays a list of the latest blog posts
  //
  public function show( $vars=NULL ) {
    $page = $this->getPage();
    $defaultLimit = 10;

    $contentDir = c::get( 'root.content' );

    // look for a custom blog folder
    if( a::get($vars, 'dir')===NULL ) {
      // use the default blog directory
      $customDir = FALSE;
      $blogDir = c::get('blog.dir' );
    } else {
      $customDir = a::get($vars, 'dir');
      $blogDir = implode( '/', explode( ':', $customDir ));
      $this->useView( basename( $blogDir ));
    }

    // build a page url from the GET vars for this blog listing
    $blogListingURL = $blogDir.'/show'.'/limit/'.a::get($vars,'limit',$defaultLimit);
    $page->set('paginationURL', $blogListingURL.'/page/');
    $blogListingURL .= '/page/'.a::get($vars,'page',1);

    $blogs = new Blogs( $contentDir . '/' . $blogDir );
    $posts = $blogs->getList( a::get( $vars, 'limit', $defaultLimit ), a::get( $vars, 'page', 1 ));

    // catch an empty set
    if( empty( $posts )) {
      $page->set( 'error', TRUE );
      return;
    }

    $page->set( 'blogs', $posts );
    $page->set( 'limitNum', a::get( $vars, 'limit', $defaultLimit ));
    $page->set( 'totalPages', ceil( $blogs->count() / a::get( $vars, 'limit', $defaultLimit )));
    $page->set( 'pageNum', a::get( $vars, 'page', 1 ));
  }

  // post()
  // shows a single post by filename
  //
  public function post( $vars=NULL ) {
    $page = $this->getPage();

    $contentDir = c::get('root.content');
    // look for a custom blog folder
    if( a::get($vars, 'dir')===NULL ) {
      // use the default blog directory
      $customDir = FALSE;
      $blogDir = c::get('blog.dir' );
    } else {
      $customDir = a::get($vars, 'dir');
      $blogDir = implode( '/', explode( ':', $customDir ));
      $this->useView( basename( $blogDir ));
      unset( $vars['dir'] );
    }

    $blogs = new Blogs( $contentDir .'/'. $blogDir );
    // $vars contains the path to the file
    // the first element in the array is [id] => blog_article
    // if the article is nested, additional items will be in the array, folded
    // into key=>value pairs. The exception is that odd pairs will be assigned a numerical array key of 0
    // we need to loop the array and build a path that exempts 'id' and '0'
    $id = array();
    foreach( $vars as $k => $v ) {
      if( $k !== 'id' && $k !== 0 ) $id[] = $k;
      $id[] = $v;
    }
    $id = a::glue( $id, '/' );
    $post = $blogs->getPost( $id );
    $page->set( 'post', $post );

    // get the post css, js, and script blocks
    foreach( (array) $post->css as $v ) $page->css($v);
    foreach( (array) $post->js as $v ) $page->js($v);
    foreach( (array) $post->script as $v ) $page->script($v);
  }

  // feed()
  //
  public function feed( $vars=NULL ) {
    $page = $this->getPage();

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
