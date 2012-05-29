<?php
//
// BlogComponent.class.php
//

// Direct access protection
if( !defined( 'LIRIOPE' )) die( 'Direct access is not allowed.' );

class BlogComponent Extends LiriopeComponent {

  // show()
  // displays a list of the latest blog posts
  //
  public function show( $vars=NULL ) {
    $base = isset( $vars['dir'] ) ? $vars['dir'] : c::get( 'blog.dir' );
    $dir = c::get( 'root.content' ) . '/' . $base;
    if( isset( $vars['dir'] )) $this->useView( basename( $dir ));

    $blogs = new Blogs( $dir );
    $posts = $blogs->getList( a::get( $vars, 'limit', 5 ), a::get( $vars, 'page', 1 ));

    return $posts;
  }

}

?>
