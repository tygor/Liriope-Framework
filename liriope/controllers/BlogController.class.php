<?php
/**
 * BlogController.class.php
 */

// sample URLs:
// --------------------
//
// These would use the controller/action method
// http://site.com/blog == http://site.com/blog/page/1
// http://site.com/blog/show/param/val
// http://site.com/blog/page/1
//
// This would default to the filepage action
// which calls the file based CMS content
// http://site.com/blog/filename
//
// so basically, if there is no action associated with the
// request structure, then use the Liriope/Folderfile action.

class BlogController Extends LiriopeController {

  public function show( $vars=NULL ) {
    $blog = new Blogs();
    $blogs = $blog->setLimit(10)->get();

    $this->set( 'blogs', $blogs );
  }

  public function post( $params=NULL ) {
    $post = new Blogposts( c::get( 'blog.dir', c::get( 'default.blog.dir' )), $params[0] );
    $this->set( 'content', $post );
  }

}

?>
