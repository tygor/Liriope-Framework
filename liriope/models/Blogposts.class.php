<?php
//
// Blogposts.class.php
//

// Direct access protection
if( !defined( 'LIRIOPE' )) die( 'Direct access is not allowed.' );

class Blogposts extends Files {

  var $pubDate;

  public function __toString() {
    // show the intro text in the full post?
    $intro = c::get( 'blog.intro.show', FALSE );

    // start output buffering and grab the full post file
    content::start();
    include( $this->fullpath );
    $post = content::end( TRUE );

    // chop off the intro text portion
    if( !$intro && $matchOffset = $this->findReadmore( $post )) {
      $post = substr( $post, $matchOffset );
      $post = substr( $post, strpos( $post, '>' )+1 );
    }
    $post = trim( $post );
    return $post;
  }

  public function getLink() {
    $info = pathinfo( $this->file );
    return 'blog/' . $info['filename'];
  }

  public function setPubDate( $date=NULL ) {
    if( $date === NULL ) return false;
    // expect a string for the date but turn it into a timestamp
    $this->pubDate = strtotime( $date );
  }
  public function getPubDate() {
    if( isset( $this->pubDate )) return $this->pubDate;
  }

  public function getIntro() {
    if( empty( $this->fullpath )) trigger_error( 'No valid file to read (file: ' . $this->file . ', path: ' . $this->path. ')', E_USER_ERROR );
    // parse the file and grab everything up to the tag holding
    // the c::get( 'readmore.class' ) class
    $post = content::get( $this->fullpath );

// TODO: Ew! this is horrible. Each blog page sets a pubdate, but it sets the "page" class with the date. So, when grabbing the intro text... how should I grab the pubDate? Maybe I need to do some text parsing rather than setting a PHP class object. a la Kirby.
    // now that we've read the page, there should be a page::set( 'blog.pubDate' ) variable
    // find it and set this blog's pub date, then unset the page variable.
    $this->setPubDate( page::get( 'blog.pubDate', NULL ));

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
