<?php
/**
 * LiriopeController.class.php
 */
useHelper( 'default' );

class LiriopeController {

	protected $_model;
	protected $_controller;
	protected $_action;
	protected $_template;

	function __construct($model, $controller, $action) {

		$this->_controller = $controller;
		$this->_action = $action;
		$this->_model = $model;

		$this->$model =& $model;
    $template = new LiriopeView($controller,$action);
		$this->_template =& $template;

    // Call in other template parts
    $navigation = new LiriopeView( 'default', 'navigation' );
    $header = new LiriopeView( 'default', 'header' );
    $header->set( 'ua', LiriopeTools::getBrowser() );
    $header->set( 'nav', $navigation->render(FALSE));

    $footer = new LiriopeView( 'default', 'footer' );
    $footer->set( 'nav', $navigation->render(FALSE));
    $this->_template->set('header', $header->render(FALSE));
    $this->_template->set('footer', $footer->render(FALSE));

	}

	function set($name,$value) {
		$this->_template->set($name,$value);
	}

	function __destruct() {
    $this->_template->render();
	}

  /**
   * For controller-less pages
   */
  public function dummyPages( $getVars=NULL )
  {
  }

}

