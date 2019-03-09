<?php

namespace Liriope\Controllers;

use Liriope\c;
use Liriope\Models\Blogs;
use Liriope\Component\Load;
use Liriope\Toolbox\A;
use Liriope\Toolbox\Str;

//
// BlogModule.class.php
//

class BlogModule Extends LiriopeModule {

  // show()
  // displays a list of the latest blog posts
  //
  // @param  string  $dir The blog directory to look for content
  // @param  int     $page The page number of results to fetch
  // @param  int     $limit The amount of entries per page
  //
  public function show( $vars=NULL ) {
    global $module;

    $contentDir = c::get( 'root.content' );
    // Hold the name of the custom view file if one was passed
    $customView = FALSE;

    // look for a custom blog folder
    if( a::get($vars, 'dir')===NULL ) {
      // use the default blog directory
      $customDir = FALSE;
      $blogDir = c::get('blog.dir' );
    } else {
      $customDir = a::get($vars, 'dir');
      $blogDir = implode( '/', explode( ':', $customDir ));
      $seek = $this->_controller . '/_' . $this->_action . '-' . basename($blogDir) . '.php';
      $customView = Load::exists( $seek );
    }

    $blogs = new Blogs( $contentDir . '/' . $blogDir );
    $posts = $blogs->getList( a::get( $vars, 'limit', 5 ), a::get( $vars, 'page', 1 ));

    // var_dump($posts);exit;

    if( $customView ) {
        $module->useView($customView);
    }

    $module->dir = $blogDir;
    $module->more = $blogDir;
    $module->posts = $posts;
  }

}

?>
