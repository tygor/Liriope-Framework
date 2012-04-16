<?php
//
// Blogposts.class.php
//

class Blogposts extends Files {

  public function __toString() {
    // show the intro text in the full post?
    $intro = c::get( 'blog.intro.show', FALSE );

    // start output buffering and grab the full post file
    ob_start();
    include($this->fullpath);
    $post = ob_get_contents();

    // chop off the intro text portion
    if( !$intro && $matchOffset = $this->findReadmore( $post )) {
      $post = substr( $post, $matchOffset );
      $post = substr( $post, strpos( $post, '>' )+1 );
    }
    $post = trim( $post );

    ob_end_clean();
    return $post;
  }

  public function getLink() {
    $info = pathinfo( $this->file );
    return 'blog/' . $info['filename'];
  }

  public function getIntro() {
    // parse the file and grab everything up to the tag holding
    // the c::get( 'readmore.class' ) class
    ob_start();
    include($this->fullpath);
    $post = ob_get_contents();
    ob_end_clean();

    // chop all but the intro text
    if( $matchOffset = $this->findReadmore( $post )) $post = substr( $post, 0, $matchOffset );

    // wrap the <h1> tag in an anchor?
    if( c::get( 'blog.link.title', TRUE )) {
      $pattern = '/(<h1[^>]*>)([a-z0-9 ]*)(<\/h1>)/i';
      $replacement = '$1<a href="' . $this->getLink() . '">$2</a>$3';
      $post = preg_replace( $pattern, $replacement, $post );
    }

    return $post;
  }

  private function findReadmore( $content=NULL ) {
    if( $content===NULL ) throw new Exception( "__CLASS__ => __METHOD__ (line __LINE__): No content was passed to search through." );

    // now, find the offset of where that class is
    $pattern = '/<[^>]*' . c::get( 'readmore.class', c::get( 'default.readmore.class', 'readmore' ) ) . '[^>]*>/';
    $count = preg_match( $pattern, $content, $matches, PREG_OFFSET_CAPTURE );

    if( $count !== 1 ) return false;
    return $matches[0][1];
  }

}

?>
