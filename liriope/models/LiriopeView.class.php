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
  protected $_theme;
	protected $variables = array();
  private $renderFile;

	public function __construct( $controller, $action ) {
		$this->_controller = $controller;
		$this->_action = $action;
    $this->setTheme( c::get( 'theme', c::get( 'default.theme' )));

    // The file should be here...
    $file = load::exists( '/' . strtolower( $controller ) . '/' . strtolower( $action ) . '.php' );
    if( !$file ) {
      throw new Exception( __CLASS__ . " can't find that view ($file)." );
      return false;
    }

    $this->renderFile = $file;
	}

	public function set($name,$value) {
		$this->variables[$name] = $value;
	}

  public function setTheme( $name=FALSE ) {
    if( !$name ) return false;
    $this->_theme = strtolower( $name );
  }

  public function getTheme() {
    return $this->_theme;
  }

  /**
   * Render the output directly to the page or optionally return the
   * generated output to caller.
   *
   * @param $direct_output Set to any non-TURE value to have the
   * output returned rather than displayed directly.
   */
  public function render( $dump=FALSE ) {
    if( $dump ) {
      page::start();
      page::render( $this->renderFile, $this->variables, $dump );
    } else {
      page::start();
      $content = page::render( $this->renderFile, $this->variables, TRUE );
      // TODO: add in a filtering chain here that combs through the $content and changes stuff
      theme::start( $this->getTheme() );
      theme::addContent( $content );
      theme::render();
    }
  }

}

