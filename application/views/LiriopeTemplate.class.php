<?php
/**
 * LiriopeTemplate.class.php
 */

class LiriopeTemplate {

	protected $variables = array();
	protected $_controller;
	protected $_action;

	function __construct($controller,$action) {
		$this->_controller = $controller;
		$this->_action = $action;

    # Define a default template variables
    $this->variables['DOCTYPE'] = "<!doctype html>";
    $this->variables['pageTitle'] = "Liriope : Monkey Grass";
	}

  /**
   * Set Variables
   */
	function set($name,$value) {
		$this->variables[$name] = $value;
	}

  /**
   * Display Template
   */
  function render() {
    extract($this->variables);

    if (file_exists(SERVER_ROOT . DS . 'application' . DS . 'views' . DS . $this->_controller . DS . 'header.php')) {
      include (SERVER_ROOT . DS . 'application' . DS . 'views' . DS . $this->_controller . DS . 'header.php');
    } else {
      include (SERVER_ROOT . DS . 'application' . DS . 'views' . DS . 'default' . DS . 'header.php');
    }

    if (file_exists(SERVER_ROOT . DS . 'application' . DS . 'views' . DS . $this->_controller . DS . $this->_action . '.php')) {
      include (SERVER_ROOT . DS . 'application' . DS . 'views' . DS . $this->_controller . DS . $this->_action . '.php');		 
    } else {
      include (SERVER_ROOT . DS . 'application' . DS . 'views' . DS . 'default' . DS . 'default' . '.php');		 
    }

    if (file_exists(SERVER_ROOT . DS . 'application' . DS . 'views' . DS . $this->_controller . DS . 'footer.php')) {
      include (SERVER_ROOT . DS . 'application' . DS . 'views' . DS . $this->_controller . DS . 'footer.php');
    } else {
      include (SERVER_ROOT . DS . 'application' . DS . 'views' . DS . 'default' . DS . 'footer.php');
    }
  }

}

