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
    // the $params passed are the URI bits and may contain extensions
    // so, clean the file extension off of the $params
    foreach( $params as $k => $param ) $params[$k] = tools::removeExtension( $param );

    // check for home page
    $path = empty( $params[0] ) ? '/home/index' : '/' . implode( '/', $params );
    $content = new Liriope( $path, c::get( 'root.web' ) . '/content' );

    // now give the view the setting from the rendered content
    //$this->_view->set( $content->get() );

    $this->render( $content->render() );
  }

  function render( $html ) {
    $this->_view->HTML = $html;
  }

  function __destruct() {
    $this->_view->load();
  }

}

?>
