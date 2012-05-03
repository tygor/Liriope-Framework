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

  function __construct($model, $controller, $action) {

    $this->_controller = strtolower( $controller );
    $this->_action = strtolower( $action );
    $this->_model =& $model;

    View::start( $this->_controller, $this->_action );

    // return this object for chaining functions
    return $this;
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

    View::set( 'content', $content->render( $content->getFile() ) );
    View::set( $content->get() );
  }

  function __destruct() {
    View::render();
  }

}

