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
    global $page;

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
    $page->set( 'content', $liriope );
  }

  // search()
  // the default search page
  //
  public function search( $params=NULL ) {
    global $page;

    $search = new search( array( 'searchfield' => 'search' ));
    $page->set( 'search', $search );
  }

  public function flush( $params=NULL ) {
    cache::flush();
    router::go();
  }

  public function mail( $encoded=NULL ) {
    global $page;
    list( $user, $host, $tld ) = explode( '+', str::rot( $encoded[0] ));
    $email = sprintf( '%s@%s.%s', $user, $host, $tld );
    $page->set( 'email', $email );
  }

  function useView( $file=NULL ) {
    global $page;
    // a custom view tells me that the route controller has been customized
    // update the $page object to match
    $page->controller = $file;
    return $this->_view->view( $file );
  }

  function load() {
    $this->_view->load();
  }

}

?>
