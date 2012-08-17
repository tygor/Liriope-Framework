<?php
//
// LiriopeModule.class.php
//

// Direct access protection
if( !defined( 'LIRIOPE' )) die( 'Direct access is not allowed.' );

class LiriopeModule {
  var $_controller;
  var $_action;
  var $_model;

  // construct()
  // Initiates the View layer and creates the $page object
  // all other controllers will add to this $page object and finally, it will be rendered
  //
  // @param  string  $model The model to use for the page
  // @param  string  $controller The controller that holds the action for this page's logic
  // @param  string  $action The method of that controller to call
  // @return self    Returns itself as an object so that other methods can be chained
  //
  function __construct($model, $controller, $action) {
    $this->_controller = strtolower( $controller );
    $this->_action = strtolower( $action );
    $this->_model =& $model;

    $file = load::exists( $controller . '/_' . $action . '.php' );
    if( !$file ) trigger_error( "We can't find that view file: $file", E_USER_ERROR );

    global $module;
    $module = new Page( $file );
  }

  public function pagination( $vars ) {
    global $module;
    $module->page = $vars['page'];
  }

  public function menu( $params=array() ) {
    global $module;

    $module->error = FALSE;
    if( !$file = load::exists( 'menu.yaml', c::get( 'root.application' ))) $module->error = TRUE;

    // extract params
    foreach($params as $k=>$v ) {
      $module->$k = $v;
    }

    $yaml = new Yaml( $file );
    $menu = new menu();
    foreach( $yaml->parse(TRUE) as $v ) {
      $menu->addChild( $v['label'], $v['url'] );
      if( isset( $v['children'] )) {
        $parent = $menu->find( $v['url'] );
        foreach( $v['children'] as $c ) {
          $parent->addChild( $c['label'], $c['url'] );
        }
      }
    }

    if( $module->page->root() !== 'home' && !$menu->findActive ) {
      $menu->findDeep( $module->page->root() )->setActive();
    }

    // set variables for the view file to use
    $module->menu = $menu;
  }

  // tweets()
  // returns the posts from the passed user
  public function tweets( $params=NULL ) {
    global $module;

    try{
      $user = a::get($params,'user',FALSE);
      if( !$user ) throw new Exception('Woops! Can\'t find that twitter user.');
      $limit = a::get($params,'limit',3);

      $twitter_xml = file_get_contents("http://api.twitter.com/1/statuses/user_timeline/".$user.".atom");
    
      if( strlen($twitter_xml) < 25) {
        // not enough data
        throw new Exception('No tweets right now.');
      }

      $doc = new DOMDocument();
      $doc->preserveWhiteSpace = false;
      if( !$doc->loadXML($twitter_xml)) {
        // Failed loading the XML data
        throw new Exception('No tweets right now.');
      }

      $tweets = array();
      $entries = $doc->getElementsByTagName("entry");

      if( $entries ) {
        foreach( $entries as $tweet ) {
          $tags = $tweet->getElementsByTagName('name'); // Username who wrote the tweet
          $name = $tags->item(0)->nodeValue;
          $tags = $tweet->getElementsByTagName('title');
          $title = $tags->item(0)->nodeValue;
          $tags = $tweet->getElementsByTagName('content');
          $content = $tags->item(0)->nodeValue;
          $tags = $tweet->getElementsByTagName('link');
          for($i=0; $i < $tags->length; $i++ ) {
            if( $tags->item($i)->getAttribute('rel') === 'alternate' ) $link = $tags->item($i)->getAttribute('href');
            if( $tags->item($i)->getAttribute('rel') === 'image' ) $icon = $tags->item($i)->getAttribute('href');
          }
          $tags = $tweet->getElementsByTagName('published');
          $date = $tags->item(0)->nodeValue;
          $timestamp = strtotime( $date );

          $tweets[] = array(
            'name' => $name,
            'title' => $title,
            'content' => $content,
            'link' => $link,
            'icon' => $icon,
            'date' => $date,
            'timestamp' => $timestamp
          );

          if( --$limit <= 0 ) break;
        }
      } else {
        throw new Exception('No tweets right now.');
      }

    } catch( Exception $e ) {
      $message->error = $e->getMessage();
    }

    $module->tweets = $tweets;
    $module->user = $user;
    $module->limit = $limit;
  }

  function setView( $name ) {
    global $module;
    $file = load::exists( $this->_controller . '/_' . $this->_action . '-' . $name . '.php' );
    if(!$file) return FALSE;
    $module->_view = $file;
  }

  function __destruct() {
    global $module;

    $html = $module->render( TRUE, 'module' );
    echo trim( $html );

    return $html;
  }

}

?>
