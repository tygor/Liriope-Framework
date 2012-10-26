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
    $module->page = a::get($vars,'page');
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

  public function submenu( $params=array() ) {
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

    $active = $menu->findActive();

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

die("this needs to be updated from my NRHC site code, which uses javascript rather than relying on the url_allow_fopen php ini setting");
      $twitter_feed = new String(@file_get_contents("http://api.twitter.com/1/statuses/user_timeline/".$user.".json"));
      $twitter_feed->parse();
    
      if( empty($twitter_feed)) {
        // not enough data
        throw new Exception('No tweets right now.');
      }

      $tweets = array_slice($twitter_feed,0,$limit);

    } catch( Exception $e ) {
      $message->error = $e->getMessage();
    }

    $module->tweets = !empty( $tweets ) ? $tweets : array();
    $module->user = $user;
    $module->limit = $limit;
  }

  function setView( $name ) {
    global $module;
    $file = load::exists( $this->_controller . '/_' . $this->_action . '-' . $name . '.php' );
    if(!$file) return FALSE;
    $module->_theme = $file;
  }

  function __destruct() {
    global $module;

    $html = $module->render( TRUE, 'module' );
    echo trim( $html );

    return $html;
  }

}

?>
