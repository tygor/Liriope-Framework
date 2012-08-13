<?php
//
// BlogModule.class.php
//

// Direct access protection
if( !defined( 'LIRIOPE' )) die( 'Direct access is not allowed.' );

class BlogModule Extends LiriopeModule {

  // show()
  // displays a list of the latest blog posts
  //
  public function show( $vars=NULL ) {
    global $module;

    // the vars may contain a reference to a custom blog directory
    // this may include nested directories in the form dir:dir:dir
    // so we'll need to strip out the path, and the final 'dir' which
    // will be the view partial to use.
    $customdir = FALSE;
    if( isset( $vars['dir'] )) {
      $dirfull = $vars['dir'];
      $dirarray = explode( ':', $dirfull );
      $base = implode( '/', $dirarray );
      $customdir = TRUE;
    } else {
      $base = c::get('blog.dir' );
    }
    $dir = c::get( 'root.content' ) . '/' . $base;
    if($customdir) {
      $file = load::exists( $this->_controller . '/_' . $this->_action . '-' . basename($base) . '.php' );
      $module->_view = $file;
    }

    $blogs = new Blogs( $dir );
    $posts = $blogs->getList( a::get( $vars, 'limit', 5 ), a::get( $vars, 'page', 1 ));

    $module->dir = $base;
    $module->more = $base;
    $module->posts = $posts;
  }

}

?>
