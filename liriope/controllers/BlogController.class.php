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

    // get the last $num of blog posts and use the Blogs model for each one
    $dir = c::get( 'root.web' ) . '/' . c::get( 'blog.dir' );
    $files = dir::read( $dir );

    // ignore certain files
    $ignore = array( '.', '..', 'index.php', 'index.html');
    $files = array_diff( $files, $ignore );

    // weed out directories and unwanted file extensions
    $filter = array( 'php', 'html', 'txt');
    foreach( $files as $k => $f ) {
      if( is_dir( $dir . '/' . $f ) || !in_array( Files::extension( $f ), $filter )) {
        unset( $files[$k] );
      }
    }

    // TODO: this part sucks because you have to read all the file to get to their PHP settings within so that they can be sorted by the pubdate rather than the file's modified date.
    // Get the list as Blogs objects
    $posts = array();
    foreach( $files as $f ) {
      $pubdate = "";
      $blog = new Blogs( c::get( 'blog.dir' ), $f );
      $blog->setContext('list');
      $pubdate = date( 'Y-m-d', $blog->getPubdate());
      $blog->pubDate = $pubdate;
      $posts[] = $blog;
    }

    // sort them by their pubdate
    uasort( $posts, array( "BlogController", "comparepubDate" ));

    // then limit to a specific number
    $limitNum = a::get( $vars, 'limit', 10 );
    $pageNum = a::get( $vars, 'page', 1);
    $startPoint = ($pageNum * $limitNum) - $limitNum;
    $postCount = count( $posts );
    $totalPages = ceil( $postCount / $limitNum );
    $posts = array_slice( $posts, $startPoint, $limitNum);

    $page->set( 'blogs', $posts );
    $page->set( 'limitNum', $limitNum );
    $page->set( 'totalPages', $totalPages );
    $page->set( 'pageNum', $pageNum );
  }

  private function comparePubDate( $a, $b ) {
    $am = $a->getPubdate();
    $bm = $b->getPubdate();
    if( $am == $bm ) return 0;
    return( $am < $bm ) ? +1 : -1;
  }

  // post()
  // shows a single post by filename
  //
  public function post( $params=NULL ) {
    global $page;

    $post = new Blogs( c::get( 'blog.dir', c::get( 'default.blog.dir' )), $params[0] );
    $post->setContext( 'show' );

    $page->set( 'post', $post );

    // now that the post has been rendered to a string, the contained PHP will have been run
    if( is_array( $post->get( 'stylesheets' ))) {
      foreach( $css as $c ) View::addStylesheet( $c['file'], $c['rel'] );
    }
  }

}

?>
