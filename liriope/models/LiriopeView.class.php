<?php
/* --------------------------------------------------
 * LiriopeView.class.php
 * --------------------------------------------------
 * handles throwing to HTML
 */

// Direct access protection
if( !defined( 'LIRIOPE' )) die( 'Direct access is not allowed.' );

class LiriopeView {

	protected $_controller;
	protected $_action;
	protected $variables = array();
  private $render;

	public function __construct( $controller, $action ) {
		$this->_controller = $controller;
		$this->_action = $action;

    // The file should be here...
    $file = load::exists( '/' . strtolower( $controller ) . '/' . strtolower( $action ) . '.php' );

    // ...but is it?
    if( file_exists( $file )) {
      // Trigger render to include the file when this object is destroyed
      $this->render = $file;
    } else {
      throw new Exception( __CLASS__ . " can't find that view ($file)." );
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
    if( $direct_output !== TRUE ) {
      ob_start();
    }

    // parse data variables into local variables
    extract($this->variables);

    // Get the template
    include( $this->render );

    // Get the contents of the buffer and return it
    if( $direct_output !== TRUE ) {
      return ob_get_clean();
    }
  }

}

