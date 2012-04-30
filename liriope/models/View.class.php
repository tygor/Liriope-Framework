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
  static $homeFlag;

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

  // isHomepage()
  // Stores if this is the homepage view
  //
  // @param  bool  $home TRUE if this is the home page
  // @return bool  TRUE if it is, and FALSE if it's any other page
  static function isHomepage( $home=FALSE ) {
    if( $home === TRUE || empty( self::$homeFlag )) self::$homeFlag = TRUE;
    if( empty( self::$homeFlag )) return FALSE;
    return self::$homeFlag;
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

  // render()
  // Render the output directly to the page or optionally return the
  // generated output to caller (which never happens).
  //
  // @param  $return  Set to any non-TURE value to have the
  // @return string   The result of the output buffer
  //
  public function render( $return=FALSE ) {
    if( $return ) {
      Page::start();
      Page::render( $this->_view, $this->variables, $dump );
    } else {
      // start the page and get it's contents as a variable
      Page::start();
      $content = Page::render( $this->_view, $this->variables, TRUE );
      // apply the page filters to the page content alone
      $content = filter::doFilters( $content );
      // then pass this page content to the theme
      // store content in an array so other variables can be stored alongside
      $vars['content'] = $content;
      $vars['themeFolder'] = 'themes/grass';
      Page::addStylesheet( $vars['themeFolder'] . '/style.css' );
      Page::addStylesheet( $vars['themeFolder'] . '/style.less', 'stylesheet/less' );
      Page::addScript( 'js/libs/less-1.3.0.min.js' );
      Page::addScriptBlock( 'less.watch();' );

      content::get( c::get( 'root.theme', 'themes' ) . '/' . self::getTheme() . '/index.php', $vars, FALSE );
    }
  }

}

