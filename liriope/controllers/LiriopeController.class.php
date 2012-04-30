<?php
/**
 * LiriopeController.class.php
 */

// Direct access protection
if( !defined( 'LIRIOPE' )) die( 'Direct access is not allowed.' );

class LiriopeController {

  protected $_model;
  protected $_controller;
  protected $_action;
  protected $_theme;
  protected $_view;

  function __construct($model, $controller, $action) {

    $this->_controller = $controller;
    $this->_action = $action;
    $this->_model = $model;
    
    $this->$model =& $model;

    $view = new View($controller,$action);
    $this->_view =& $view;

    // return this object for chaining functions
    return $this;
  }

  function set($name,$value) {
    $this->_view->set($name,$value);
  }

  public function show( $getVars=NULL ) {
  }

  // TODO: think of a new/better name for this function, related model, and view
  public function filepage( $params=NULL ) {
    // clean the file extension off of the $params
    foreach( $params as $k => $param ) {
      $params[$k] = preg_replace( '/\.[^.]+/', '', $param );
    }

    // check for home page
    $path = empty( $params[0] ) ? '/home/index' : '/' . implode( '/', $params );
    $content = new Folderfile( $path, c::get( 'root.content' ));

    $this->set( 'content', $content->get() );
  }

  function __destruct() {
    $this->_view->render();
  }

}

