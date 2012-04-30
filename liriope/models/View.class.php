<?php

// Direct access protection
if( !defined( 'LIRIOPE' )) die( 'Direct access is not allowed.' );

//
// View.class.php
// handles throwing to HTML
// all methods are be static
//

class View {

	static protected $_controller;
	static protected $_action;
  static protected $_theme;
	static protected $variables = array();
  static private $_view;
  static $homeFlag;
  static $css = array();
  static $scripts = array();
  static $scriptBlocks = array();

  static function start( $controller, $action ) {
		self::$_controller = $controller;
		self::$_action = $action;
    self::setTheme( c::get( 'theme', c::get( 'default.theme' )));

    $file = load::exists( '/' . $controller . '/' . $action . '.php' );
    if( !$file ) trigger_error( "We can't find that view file: $file", E_USER_ERROR );

    // this is the controller/action pair as folder/file
    self::$_view = $file;
	}

  // set()
  // Stores name/value pairs for usage in the render() method
  //
  // @param  mixed  $name The name for the value
  // @param  mixed  $value The value to set
  // 
	static function set( $name, $value ) {
		self::$variables[$name] = $value;
    return self::$variables[$name];
	}
  static function get( $name=NULL, $default=FALSE  ) {
    // even though self::$variables is extracted into the symbol table
    // sometimes, you may want to get a configuration value as a default
    // so get will return the self::$variable[$name] or the c::get( $name )
    if( !empty( self::$variables[$name] )) return self::$variables[$name];
    if( c::get( $name )) return c::get( $name );
    return $default;
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
  static function setTheme( $name=NULL ) {
    if( $name === NULL ) return false;
    self::$_theme = strtolower( $name );
  }

  // getTheme()
  // Returns the set theme name if there is one.
  //
  // @return string Returns the theme name as a string or FALSE on error
  // 
  static function getTheme() {
    if( isset( self::$_theme )) return self::$_theme;
    return FALSE;
  }

  // addStylesheet()
  // Queues the passed file to be loaded as a stylesheet as the page renders
  // 
  // @param  $file  The stylesheet to be used
  // @param  $rel   The value for the link rel param
  // @return bool   TRUE on success, FALSE on error
  //
  static function addStylesheet( $file=NULL, $rel='stylesheet' ) {
    if( $file===NULL ) return false;
    self::$css[] = array( 'file' => $file, 'rel' => $rel );
    return true;
  }

  static function getStylesheets() {
    return (array) self::$css;
  }

  // addScript()
  // Queues the passed file to be loaded as a script as the page renders
  // 
  // @param  $file  The script file to be used
  // @param  $type  The value for the script type param
  // @return bool   TRUE on success, FALSE on error
  //
  static function addScript( $file=NULL, $type='text/javascript' ) {
    if( $file===NULL ) return false;
    self::$scripts[] = array( 'file' => $file, 'type' => $type );
    return true;
  }

  static function getScripts() {
    return (array) self::$scripts;
  }

  // addScriptBlock()
  // Queues the passed string into a script tag as the page renders
  // 
  // @param  $file  The script block
  // @return bool   TRUE on success, FALSE on error
  //
  static function addScriptBlock( $script=NULL ) {
    if( $script===NULL ) return false;
    self::$scriptBlocks[] = $script;
    return true;
  }

  static function getScriptBlocks() {
    return (array) self::$scriptBlocks;
  }


  // render()
  // Render the output directly to the page or optionally return the
  // generated output to caller (which never happens).
  //
  // @param  $return  Set to any non-TURE value to have the
  // @return string   The result of the output buffer
  //
  static function render( $return=FALSE ) {
    $vars = self::$variables;

    if( $return ) {
      // return the buffer as a string
      $page = new Page();
      return $page->render( self::$_view, self::$variables, $return );
    } else {

      // start the page and get it's contents as a variable
      $page = new Page();
      $content = $page->render( self::$_view, self::$variables, TRUE );

      // apply the page filters to the page content alone
      $content = filter::doFilters( $content );

      // then pass this page content to the theme
      // store content in an array so other variables can be stored alongside
      // these get extracted into the variable table for the view files
      // (ex: $vars['content'] will become $content)
      $vars['content'] = $content;
      $vars['themeFolder'] = 'themes/grass';
      // temporary devleopment add-ins TODO: remove these
      self::addStylesheet( $vars['themeFolder'] . '/style.css' );
      self::addStylesheet( $vars['themeFolder'] . '/style.less', 'stylesheet/less' );
      self::addScript( 'js/libs/less-1.3.0.min.js' );
      self::addScriptBlock( 'less.watch();' );

      // get content and dump!
      content::get( c::get( 'root.theme', 'themes' ) . '/' . self::getTheme() . '/index.php', $vars, FALSE );
    }
  }

}

