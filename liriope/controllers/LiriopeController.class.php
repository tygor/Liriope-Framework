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
  protected $_page;

  function __construct($model, $controller, $action) {

    $this->_controller = $controller;
    $this->_action = $action;
    $this->_model = $model;
    
    $this->$model =& $model;

    $page = new LiriopeView($controller,$action);
    $this->_page =& $page;

    // return this object for chaining functions
    return $this;
  }

  function set($name,$value) {
    $this->_page->set($name,$value);
  }

  function __destruct() {
    $this->_page->render();
  }

}

