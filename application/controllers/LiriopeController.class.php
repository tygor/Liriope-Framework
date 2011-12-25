<?php
/**
 * LiriopeController.class.php
 */

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

    // Set some default variables
    // TODO: I'd like to be able to call these template parts from a controller
    //       like $header = getTemplate( 'default' 'header' ) but that controller
    //       needs to be able to assign variables that can be read during this
    //       __destruct function as the templates are stored then spit out.
    $header = new LiriopeView( 'default', 'header' );
    #$header = addPart( 'default', 'header' ); // $controller, $action
    $user_agent = LiriopeTools::getBrowser();
    $header->set( 'ua', $user_agent );

    $footer = new LiriopeView( 'default', 'footer' );
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

