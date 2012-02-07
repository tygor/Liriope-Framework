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

  function isHomepage() {
    // assume that if the default controller and action are used that this is the homepage
    // TODO: this is more of a URI decision so perhaps a URI class is needed
    if( $this->_controller == c::get( 'default.controller' ) &&
      $this->_action == c::get( 'default.action' )) {
      return true;
    } else {
      return false;
    }
  }

  function set($name,$value) {
    $this->_page->set($name,$value);
  }

  function __destruct() {
    $this->_page->render();
  }

  /* --------------------------------------------------
   * Default actions
   * --------------------------------------------------
   */
  public function dummyPages( $getVars=NULL )
  {
  }

}

