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

    $contentDir = c::get( 'root.content' );

    // look for a custom blog folder
    if( a::get($vars, 'dir')===NULL ) {
      // use the default blog directory
      $customDir = FALSE;
      $blogDir = c::get('blog.dir' );
    } else {
      $customDir = a::get($vars, 'dir');
      $blogDir = implode( '/', explode( ':', $customDir ));
      $seek = $this->_controller . '/_' . $this->_action . '-' . basename($blogDir) . '.php';
      $customView = load::exists( $seek );
    }

    $blogs = new Blogs( $contentDir . '/' . $blogDir );
    $posts = $blogs->getList( a::get( $vars, 'limit', 5 ), a::get( $vars, 'page', 1 ));

    if( $customView ) { $module->_view = $customView; }

    $module->dir = $blogDir;
    $module->more = $blogDir;
    $module->posts = $posts;
  }

}

?>
