<?php
//
// Messages.class.php
//

// Direct access protection
if( !defined( 'LIRIOPE' )) die( 'Direct access is not allowed.' );

define( 'GA_PAGEVIEW_CODE', " onClick=\"javascript:pageTracker._trackPageview('/internal-links/messages/%s');\"");
define( 'MESSAGE_DATE_FORMAT', "Y-m-d" );

class Messages extends obj {
  var $seriesTitle;
  var $seriesBumper;
  var $seriesImage = array( "url", "label" );
  var $isCurrent = false;
  var $isComing = false;
  var $params;
  var $errors;
  var $hasVideo = FALSE;
  var $tracking;
  var $messages = array();

  public function __construct( $xml, $params = array() )
  {
    // get the parameters
    $this->params = $params;
    
    // now build the series from the XML file
    // test then set each item of the XML file into my object
    $this->seriesTitle = !empty($xml->seriesTitle) ? $xml->seriesTitle : false;
    $this->seriesImage = !empty($xml->seriesImage) ? $xml->seriesImage : false;
    $this->seriesBumper = !empty($xml->seriesBumper) ? $xml->seriesBumper : false;
    if( isset( $xml->flags ) && $xml->flags->current == "true" ) $this->isCurrent = true;
    elseif( isset( $xml->flags ) && $xml->flags->coming == "true" ) $this->isComing = true;
    
    // build the messages if there are any
    if( isset($xml->messages) && isset($xml->messages->message) )
    {
      foreach( $xml->messages->message as $message )
      {
        $this->messages[] = new Message( $message );
      }
    }
  }
  
  // get()
  // parameter retrieval
  //
  public function get( $name = NULL, $default=FALSE )
  {
    if ( $name===NULL ) return false;
    return empty( $this->params[$name] ) ? $default : $this->params[$name];
  }
  
  // hasVideo()
  // checks of any video is defined as a conditional to showing video
  //
  function hasVideo() {
    if( $this->hasVideo ) return TRUE;
    if( !empty( $this->seriesBumper )) {
      $this->hasVideo = TRUE;
      return TRUE;
    }
    $latest = $this->getLastMessage();
    if( isset( $latest->media['watch'] ) && $latest->media['watch']->url ) {
      $this->hasVideo = TRUE; 
      return TRUE;
    }
    return FALSE;
  }

  // getVideo()
  // gets the series bumper video url or the latest message url
  //
  function getVideo() {
    if( !$this->hasVideo() ) {
      return FALSE;
    }

    switch( $this->get('play', 'play')) {
      default:
      case "last":
      case "latest":
        $seriesTitle = $this->seriesTitle;
        if( $last = $this->getLastMessage() ) {
          $date = date( MESSAGE_DATE_FORMAT, $last->date );
          $lastURL = $last->media['watch']->url;
        }
        if( $last && !empty( $lastURL )) {
          $this->tracking = sprintf(GA_PAGEVIEW_CODE, "$seriesTitle/$date/watch.html");
          return $lastURL . "&autostart=1";
        }
        return FALSE;
        break;
      case "bumper":
        if( !empty( $this->seriesBumper ) && !empty( $this->seriesBumper->url )) {
          $this->tracking = sprintf(GA_PAGEVIEW_CODE, $this->seriesTitle . "/bumper.html");
          return $this->seriesBumper->url . '&autostart=1';
        }
        return FALSE;
        break;
    }

    return FALSE;
  }

  // getLastMessage()
  // returns the last message object based on date
  //
  private function getLastMessage()
  {
    if( !is_array($this->messages) ) { return false; }
    
    $latest = NULL;
    foreach( $this->messages as $key => $message ) {
      if( is_null($latest) || $message->getDate() > $this->messages[$latest]->getDate() ) {
        $latest = $key;
      }
    }
    if( $latest===NULL ) return FALSE;
    return $this->messages[$latest];
  }

}

class Message extends obj {
  var $date;
  var $title;
  var $speaker;
  var $media = array();

  public function __construct( $object = NULL )
  {
    $this->date = strtotime( $object->date );
    $this->title = $object->title;
    $this->speaker = $object->speaker;
    if( is_object( $object->media ) && count( $object->media ) ) {
      foreach( $object->media->item as $item ) {
        $type = strtolower($item->type);
        $this->media[$type] = new Media( $type, $item->url );
      }
    }
  }

  public function getDate() {
    return date( MESSAGE_DATE_FORMAT, $this->date );
  }
}

class Media extends obj {
  var $type;
  var $url;

  public function __construct( $type = NULL, $url = NULL ) {
    $this->type = $type;
    $this->url = $url;
  }
}

?>
