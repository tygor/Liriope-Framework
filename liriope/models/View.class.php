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

    // always add these as defaults
    self::addStylesheet( self::get( 'themeFolder' ) . '/style.css' );
    self::addScript( self::get( 'themeFolder' ) . '/script.js' );

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
	static function set( $name, $value=NULL ) {
    // if $name is an array, then set multiple values
    if( is_array( $name )) {
      foreach( $name as $k => $v ) {
        // if $v is still an array, simply add that array to the array of $k
        if( is_array( $v )) self::$variables[$k][] = $v;
        else self::$variables[$k] = $v;
      }
    } else {
      self::$variables[$name] = $value;
    }
	}

  static function get( $name=NULL, $default=FALSE  ) {
    if( $name === NULL ) return self::$variables;
    // even though self::$variables is extracted into the symbol table
    // sometimes, you may want to get a configuration value as a default
    // so get will return the self::$variable[$name] or the c::get( $name )
    if( !empty( self::$variables[$name] )) return self::$variables[$name];
    if( c::get( $name )) return c::get( $name );
    return $default;
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
    self::set( 'themeFolder', c::get( 'theme.folder' ) . '/' . self::$_theme );
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
    self::$variables['stylesheets'][] = array( 'file' => $file, 'rel' => $rel );
    return true;
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
    self::$variables['scripts'][] = array( 'file' => $file, 'type' => $type );
    return true;
  }

  // addScriptBlock()
  // Queues the passed string into a script tag as the page renders
  // 
  // @param  $file  The script block
  // @return bool   TRUE on success, FALSE on error
  //
  static function addScriptBlock( $script=NULL ) {
    if( $script===NULL ) return false;
    self::$variables['scriptBlocks'][] = $script;
    return true;
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
      $vars = self::get();
      $vars['content'] = $content;
      $vars['themeFolder'] = c::get( 'theme.folder', 'themes' ) . '/' . c::get( 'theme', c::get( 'default.theme' ));

      // get content and dump!
      content::start();
      if( is_array( $vars )) extract( $vars );
      include( c::get( 'root.theme', 'themes' ) . '/' . self::getTheme() . '/index.php' );
      $buffer = content::end();
      echo $buffer;
    }
  }

}

