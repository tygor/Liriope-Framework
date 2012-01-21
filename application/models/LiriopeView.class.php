<?php
/**
 * LiriopeTemplate.class.php
 */

class LiriopeView {

	protected $_controller;
	protected $_action;
  // Holds template variables
	protected $variables = array();
  // Holds render status of view.
  private $render;

	public function __construct( $controller, $action ) {
		$this->_controller = $controller;
		$this->_action = $action;

    // Define a default template variables
    // TODO: later to be defined within some sort of config file
    $this->set( 'DOCTYPE', '<!doctype html>');
    $this->set('pageTitle', 'Liriope : Monkey Grass');

    $file = SERVER_ROOT . DS . 'application' . DS . 'views' . DS . strtolower($controller) . DS . strtolower($action) . '.php';
    if( file_exists( $file ))
    {
      // Trigger render to include the file when this object is destroyed
      $this->render = $file;
    }
    else
    {
      throw new Exception( __METHOD__ . " can't find that view template ($file)." );
    }
	}

  /**
   * Set Variables
   */
	public function set($name,$value) {
		$this->variables[$name] = $value;
	}

  /**
   * Render the output directly to the page or optionally return the
   * generated output to caller.
   *
   * @param $direct_output Set to any non-TURE value to have the
   * output returned rather than displayed directly.
   */
  public function render( $direct_output = TRUE ) {
    // Turn output buffering on capturing all output
    if( $direct_output !== TRUE )
    {
      ob_start();
    }

    // parse data variables into local variables
    extract($this->variables);

    // Get the template
    include( $this->render );

    // Get the contents of the buffer and return it
    if( $direct_output !== TRUE )
    {
      return ob_get_clean();
    }
  }

  public function __destruct()
  {
  }

}

