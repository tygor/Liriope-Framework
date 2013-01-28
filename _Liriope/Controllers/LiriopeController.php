<?php

use Liriope\Toolbox\A;
use Liriope\Models\Liriope;
use Liriope\Models\View;

/**
 * LiriopeController.class.php
 */

class LiriopeController {

  // @var string The name of the model to use
  // TODO: This variable is not being used. Find a purpose or remove.
  private $_model;
  // @var string The name of the controller to be used
  private $_controller;
  // @var string The name of the action to call in the controller
  private $_action;
  // @var string The name of the theme to wrap the view in during render
  private $_theme;
  // @var string The name of the view file to buffer during render
  private $_view;

  // construct()
  // Initiates the View layer and creates the $page object
  // all other controllers will add to this $page object and finally, it will be rendered
  //
  // @param  string  $model The model to use for the page
  // @param  string  $controller The controller that holds the action for this page's logic
  // @param  string  $action The method of that controller to call
  // @return self    Returns itself as an object so that other methods can be chained
  //
  public function __construct($model, $controller, $action) {
    $this->_controller = strtolower( $controller );
    $this->_action = strtolower( $action );
    $this->_model =& $model;
    $this->_view = new View( $this->_controller, $this->_action );
  }

  // show()
  // The default Liriope action that reads content from the web/content folder
  //
  public function show( $params=NULL ) {
    $page = $this->getPage();

    // the $params passed are the URI bits and may contain extensions
    // so, clean the file extension off of the $params,
    // and stretch the keys and values into an array of values
    $path = array();
    foreach( $params as $k => $v ) {
      if( $k ) $path[] = tools::removeExtension( $k );
      if( $v ) $path[] = tools::removeExtension( $v );
    }

    // check for home page
    if( empty( $path )) $path = array( 'home' );
    $path = '/' . a::glue( $path, '/' );
    $liriope = new Liriope( $path, c::get( 'root.web' ) . '/content' );

    // render the Liriope model using it's __toString function
    // this will store the new $page variables that are in the view and content files
    $page->set( 'content', $liriope->render( $page ));
  }

  /**
   * serach()
   * the default search page
   */
  public function search( $params=NULL ) {
    $page = $this->getPage();
    c::set( 'cache', FALSE);

    $search = new search( array( 'searchfield' => 'q', 'ignore'=>c::get('search.ignore', array('home','search'))));
    $page->set( 'search', $search );
  }

  public function flush( $params=NULL ) {
    cache::flush();
    router::go();
  }

  public function crawl( $params=NULL ) {
    $page = $this->getPage();
    $visited = crawler::crawl();
    $count = count((array)$visited);
    $page->set('visited', $visited);
    $page->set('crawled', $count);
  }

  public function mail( $encoded=NULL ) {
    $page = $this->getPage();
    $covert_email = new String($encoded[0]);
    list( $user, $host, $tld ) = $covert_email->rot()->split('+');
    $email = sprintf( '%s@%s.%s', $user, $host, $tld );
    $page->set( 'email', $email );
  }

  public function redirect( $params=NULL ) {
    $destination = a::get($params, 'url');
    if(!$url = parse_url(urldecode($destination))) trigger_error('That\'s not a good URL.');
    if(isset($url['scheme']) && substr($url['scheme'],0,4) == 'http') {
      go(urldecode($destination));
    }
    go();
  }

  function useView( $modifier=NULL ) {
    $page = $this->getPage();

    $check = $this->_controller . '/' . $this->_action . '_' . $modifier . '.php';
    $file = load::exists( $check );
    if( !$file ) {
        $fallback = $this->_controller . '/' . $this->_action . '.php';
        $file = load::exists( $fallback );

        if( !$file ) {
            throw new Exception("The view file you are attempting to use ($check) cannot be found");
        }
    }
    $page->useView($file);
  }

  function getPage() {
    $page = &$this->_view->_page;
    return $page;
  }

  function load() {
    $this->_view->load();
  }

}
