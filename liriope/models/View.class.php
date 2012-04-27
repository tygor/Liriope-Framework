<?php

// Direct access protection
if( !defined( 'LIRIOPE' )) die( 'Direct access is not allowed.' );

// --------------------------------------------------
// View.class.php
// --------------------------------------------------
// handles throwing to HTML
//

class View {

	protected $_controller;
	protected $_action;
  protected $_theme;
	protected $variables = array();
  private $_view;

	public function __construct( $controller, $action ) {
		$this->_controller = strtolower( $controller );
		$this->_action = strtolower( $action );
    $this->setTheme( c::get( 'theme', c::get( 'default.theme' )));

    // The view template file
    $file = load::exists( '/' . $this->_controller . '/' . $this->_action . '.php' );
    if( !$file ) {
      throw new Exception( __CLASS__ . " can't find that view ($file)." );
      return false;
    }

    $this->_view = $file;
	}

  // set()
  // Stores name/value pairs for usage in the render() method
  //
  // @param  mixed  $name The name for the value
  // @param  mixed  $value The value to set
  // 
	public function set($name,$value) {
		$this->variables[$name] = $value;
	}

  // setTheme()
  // Stores a string name for the desired theme. This is turned into the
  // path locating the theme files.
  //
  // @param  string  $name The theme name which is also the theme folder
  // 
  public function setTheme( $name=NULL ) {
    if( $name === NULL ) return false;
    $this->_theme = strtolower( $name );
  }

  // getTheme()
  // Returns the set theme name if there is one.
  //
  // @return string Returns the theme name as a string or FALSE on error
  // 
  public function getTheme() {
    if( isset( $this->_theme )) return $this->_theme;
    return FALSE;
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
      Page::start();
      Page::render( $this->_view, $this->variables, $dump );
    } else {
      // start the page and get it's contents as a variable
      Page::start();
      $content = Page::render( $this->_view, $this->variables, TRUE );
      // apply the page filters to the page content alone
      $content = filter::doFilters( $content );
      // then pass this page content to the theme
      theme::start( $this->getTheme() );
      theme::addContent( $content );
      // and finally, render the theme and page content
      theme::render();
    }
  }

}

