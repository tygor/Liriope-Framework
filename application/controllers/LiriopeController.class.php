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
    $template = new LiriopeTemplate($controller,$action);
		$this->_template =& $template;

	}

	function set($name,$value) {
		$this->_template->set($name,$value);
	}

	function __destruct() {
			$this->_template->render();
	}

}

