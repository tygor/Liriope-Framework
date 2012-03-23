<?php
/**
 * TumblrModel.class.php
 */

// --------------------------------------------------------------------------------
// TumblrModel
// extends XmlModel
// --------------------------------------------------------------------------------
class TumblrModel extends XmlModel {
  protected $feed;
  protected $params = array();
  protected $isRead = false;
  var $name;
  var $href;
  var $timezone;
  var $title;
  var $startPost;
  var $totalPosts;
  var $_posts = array();

  public function __construct( $feed=false, $params=array() ) {
    if( !$feed ) return false;
    $this->setFeed( $feed );
    $this->set( $params );
    return $this;
  }

  // set
  //
  // takes a variable or array and stores it in the
  // object variable $this->params
  public function set( $key=NULL, $val=NULL ) {
    if( $key === NULL || $val === NULL ) return false;
    if( is_array( $key )) {
      foreach( $key as $k => $v ) {
        $this->params[$k] = $v;
      }
      return true;
    }
    $this->params[$key] = $val;
    return $this;
  }

  // setFeed
  //
  // sets the url to the Tumblr feed
  public function setFeed( $feed=false ) {
    if( !$feed ) return false;
    $this->feed = $feed;
    $this->setFlag = true;
  }

  // get
  //
  // chainfire here!
  // compiles the feed url and params
  // sends to the XML model and receives the XML object
  // then, parses that into Tumblr feed info and posts
  // returns the posts array
  function get( $name=false )
  {
    // take the params and add to the feed url
    $params = '';
    if( !empty( $this->params )) {
      $paramarray = array();
      foreach( $this->params as $k => $v ) {
        $paramarray[] = "$k=$v";
      }
      $params = "?" . implode( "&", $paramarray );
    }

    // then set the file on the parent object
    if( !$this->setFlag ) return false;
    $feedurl = $this->feed . $params;
    parent::setFile( $feedurl, true );

    // load it
    parent::loadFile();

    // and if the checks are passed, give them the xml
    if( $this->setFlag && $this->loadFlag ) {
      if( !$this->isRead ) {
        $this->readTumblr( $this->_xml );
      }
      return $this->_posts;
    }
    return false;
  }

  private function readTumblr( $objXml=NULL ) {
    $this->name = $objXml->tumblelog['name'];
    $this->href = "http://" . $this->name . ".tumblr.com";
    $this->title = $objXml->tumblelog['title'];
    $this->timezone = $objXml->tumblelog['timezone'];
    $this->startPost = $objXml->posts['start'];
    $this->totalPosts = $objXml->posts['total'];
    $this->addPosts( $objXml->posts ); 
    $this->isRead = true;
  }

  private function addPosts( $post=NULL ) {
    if( $post === NULL ) return false;
    // loops through the posts and creates objects based on type
    foreach( $post->post as $v ) {
      $type = $v['type'];
      $obj = "Tumblr" . ucfirst( $type );
      $obj = new $obj($v);
      $this->_posts[] = $obj;
    }
    return true;
  }
}

// --------------------------------------------------------------------------------
// TumblrItem
// --------------------------------------------------------------------------------
class TumblrItem {
  var $id;
  var $url;
  var $urlWithSlug;
  var $type;
  var $dateGMT;
  var $date;
  var $unixTimestamp;
  var $format;
  var $reblogKey;
  var $slug;

  public function setAttributes( $attrs=NULL ) {
    if( $attrs === NULL ) return false;
    $this->id = $attrs['id'];
    $this->url = $attrs['url'];
    $this->urlWithSlug = $attrs['url-with-slug'];
    $this->type = $attrs['type'];
    $this->dateGMT = $attrs['date-gmt'];
    $this->date = $attrs['date'];
    $this->unixTimestamp = $attrs['unix-timestamp'];
    $this->format = $attrs['format'];
    $this->reblogKey = $attrs['reblog-key'];
    $this->slug = $attrs['slug'];
  }
}

// --------------------------------------------------------------------------------
// TumblrLink
// --------------------------------------------------------------------------------
class TumblrLink extends TumblrItem {
  var $linkText;
  var $linkUrl;
  var $bookmarklet;

  public function __construct( $params=NULL ) {
    if( $params === NULL ) return false;
    parent::setAttributes( $params );
    $this->bookmarklet = $params['bookmarklet'];
    $this->linkText = $params->{'link-text'};
    $this->linkUrl = $params->{'link-url'};
  }

  public function __toString() {
    $output = '';
    $linkString = '<a href="%s" target="_blank">%s</a>';
    if( empty( $this->linkText )) return false; 
    $output .= sprintf( $linkString,
                        $this->linkUrl,
                        $this->linkText
                      );
    return $output;
  }
}

// --------------------------------------------------------------------------------
// TumblrPhoto
// --------------------------------------------------------------------------------
class TumblrPhoto extends TumblrItem {
  var $width;
  var $height;
  var $caption;
  var $linkUrl;
  var $sourceUrl = array();
  var $photos = array();
  protected $maxWidth;

  public function __construct( $params=NULL ) {
    if( $params === NULL ) return false;
    parent::setAttributes( $params );
    $this->width = $params['width'];
    $this->height = $params['height'];
    $this->caption = $params->{'photo-caption'};
    $this->linkUrl = $params->{'photo-link-url'};
    $this->sourceUrl = $params->{'photo-url'};
    // TODO: create a class to catch photosets, a sub-variety of the photo type
  }

  public function __toString() {
    $output = '';
    $photoString = '<p><img src="%s" width="%d" height="%d" alt="Tumblr photo">%s</p>';
    $linkString = '<a href="%s" target="_blank">%s</a>';
    $output .= sprintf( $photoString,
                        $this->getPhoto( $this->getMaxWidth()),
                        $this->determineWidth(),
                        $this->determineHeight(),
                        $this->caption
                      );
    if( !empty( $this->linkUrl )) $output = sprintf( $linkString, $this->linkUrl, $output );
    else $output = sprintf( $linkString, $this->sourceUrl, $output );
    return $output;
  }

  public function setMaxWidth( $width=75 ) {
    $this->maxWidth = $width;
  }

  public function getMaxWidth( $default=75 ) {
    if( isset( $this->maxWidth )) return $this->maxWidth;
    return $default;
  }

  public function determineWidth() {
    // use getMaxWidth to proportionally scale the actual width
    // the formula is getMaxWidth * actualHeight / actualWidth
    $val = $this->getMaxWidth() * $this->height / $this->width;
    return $val;
  }

  public function determineHeight() {
    // use getMaxWidth to proportionally scale the actual height
    // the formula is getMaxWidth * actualHeight / actualWidth
    $val = $this->getMaxWidth() * $this->height / $this->width;
    return $val;
  }

  public function getPhoto( $maxWidth=NULL ) {
    // Tumblr seems to set standard photo sizes of Original, 500, 400, 250, 100, and 75
    switch( $maxWidth ) {
      case ( $maxWidth <= 75 ):
        return $this->sourceUrl[5];
        break;
      case ( $maxWidth <= 100 ):
        return $this->sourceUrl[4];
        break;
      case ( $maxWidth <= 250) :
        return $this->sourceUrl[3];
        break;
      case ( $maxWidth <= 400) :
        return $this->sourceUrl[2];
        break;
      case ( $maxWidth <= 500 ):
        return $this->sourceUrl[1];
        break;
      default:
        return $this->sourceUrl[0];
        break;
    }
    return false;
  }
}

// TODO setup classes for any other Tumlbr post types with their own unique values

?>
