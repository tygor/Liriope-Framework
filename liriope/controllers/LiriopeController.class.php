<?php
/**
 * LiriopeController.class.php
 */

// Direct access protection
if( !defined( 'LIRIOPE' )) die( 'Direct access is not allowed.' );

class LiriopeController {

  var $_model;
  var $_controller;
  var $_action;
  var $_theme;
  var $_view;

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
    
    // now that it has been rendered, the page variables are loaded
  }

  // search()
  // the default search page
  //
  public function search( $params=NULL ) {
    $page = $this->getPage();
    c::set( 'cache', FALSE);

    $search = new search( array( 'searchfield' => 'search', 'ignore'=>c::get('search.ignore', array('home','search'))));
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

  function useView( $file=NULL ) {
    $page = $this->getPage();
    // a custom view tells me that the route controller has been customized
    // update the $page object to match
    $page->controller = $file;
    return $this->_view->view( $file );
  }

  function getPage() {
    $page = &$this->_view->_page;
    return $page;
  }

  function load() {
    $this->_view->load();
  }

}

?>
