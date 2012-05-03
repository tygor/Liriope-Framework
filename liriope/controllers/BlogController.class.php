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
    // get the last $num of blog posts and use the Blogs model for each one
    $dir = c::get( 'root.web' ) . '/' . c::get( 'blog.dir' );
    $files = dir::read( $dir );

    // ignore certain files
    $ignore = array( '.', '..', 'index.php', 'index.html');
    $files = array_diff( $files, $ignore );

    // weed out directories and unwanted file extensions
    $filter = array( 'php', 'html', 'txt');
    foreach( $files as $k => $f ) {
      if( is_dir( $dir . '/' . $f ) || !in_array( Files::extension( $f ), $filter )) unset( $files[$k] );
    }

    // TODO: this part sucks because you have to read all the file to get to their PHP settings within so that they can be sorted by the pubdate rather than the file's modified date.
    // Get the list as Blogs objects
    $posts = array();
    foreach( $files as $f ) {
      $blog = new Blogs( c::get( 'blog.dir' ), $f );
      $blog->setContext('list');
      $pubdate = $blog->get( 'blog.pubDate', FALSE );
      if( $pubdate === FALSE ) {
        $pubdate = date( 'Y-m-d', Files::modified( $blog->fullpath ));
        $blog->set( 'blog.pubDate', $pubdate );
      }
      $posts[$pubdate] = $blog;
    }

    // sort them by their pubdate
    krsort( $posts );

    // then limit to a specific number
    $limitNum = 5;
    $posts = array_slice( $posts, 0, $limitNum);

    View::set( 'blogs', $posts );
  }

  // post()
  // shows a single post by filename
  //
  public function post( $params=NULL ) {
    $post = new Blogs( c::get( 'blog.dir', c::get( 'default.blog.dir' )), $params[0] );
    View::set( 'post', $post );
    // now that the post has been rendered to a string, the contained PHP will have been run
    if( is_array( $post->get( 'stylesheets' ))) {
      foreach( $css as $c ) View::addStylesheet( $c['file'], $c['rel'] );
    }
    View::set( $post->get() );
  }

}

?>
