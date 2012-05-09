<?php

// Direct access protection
if( !defined( 'LIRIOPE' )) die( 'Direct access is not allowed.' );

//
// View.class.php
// handles throwing to HTML
//

class View extends obj {

	 var $_site;
	 var $_page;
	 var $_controller;
	 var $_action;
   var $_theme;
   var $_view;
	 var $variables = array();
   var $homeFlag;
   var $css = array();
   var $scripts = array();
   var $scriptBlocks = array();

   function __construct( $controller, $action ) {
		$this->_controller = $controller;
		$this->_action = $action;

    // always add these as defaults
    $this->addStylesheet( $this->get( 'themeFolder' ) . '/style.css' );
    $this->addScript( $this->get( 'themeFolder' ) . '/script.js' );

    $file = load::exists( $controller . '/' . $action . '.php' );
    if( !$file ) trigger_error( "We can't find that view file: $file", E_USER_ERROR );

    // this is the controller/action pair as folder/file
    $this->_view = $file;

    $this->_site = new Site();
    // TODO: NO! This IS page... View = Page. And some of page = Liriope model.
    $this->_page = new Page( $file );
	}

  // theme()
  // Stores a string name for the desired theme. This is turned into the
  // path locating the theme files.
  //
  // @param  string  $name The theme name which is also the theme folder
  // 
  function theme( $name=NULL ) {
    if( $name===NULL ) return isset( $this->_theme ) ? $this->_theme : c::get( 'default.theme' );
    $this->_theme = strtolower( $name );
    $this->set( 'themeFolder', c::get( 'theme.folder' ) . '/' . $this->_theme );
  }

  // addStylesheet()
  // Queues the passed file to be loaded as a stylesheet as the page renders
  // 
  // @param  $file  The stylesheet to be used
  // @param  $rel   The value for the link rel param
  // @return bool   TRUE on success, FALSE on error
  //
  function addStylesheet( $file=NULL, $rel='stylesheet' ) {
    if( $file===NULL ) return false;
    $this->variables['stylesheets'][] = array( 'file' => $file, 'rel' => $rel );
    return true;
  }

  // addScript()
  // Queues the passed file to be loaded as a script as the page renders
  // 
  // @param  $file  The script file to be used
  // @param  $type  The value for the script type param
  // @return bool   TRUE on success, FALSE on error
  //
  function addScript( $file=NULL, $type='text/javascript' ) {
    if( $file===NULL ) return false;
    $this->variables['scripts'][] = array( 'file' => $file, 'type' => $type );
    return true;
  }

  // addScriptBlock()
  // Queues the passed string into a script tag as the page renders
  // 
  // @param  $file  The script block
  // @return bool   TRUE on success, FALSE on error
  //
  function addScriptBlock( $script=NULL ) {
    if( $script===NULL ) return false;
    $this->variables['scriptBlocks'][] = $script;
    return true;
  }

  // load()
  // Render the output directly to the page or optionally return the
  // generated output to caller (which never happens).
  //
  // @param  $return  Set to any non-TURE value to have the
  // @return string   The result of the output buffer
  //
  function load() {
    global $page;

    $site =& $this->_site;
    $page = $this->_page;

    // tell the theme object about the site and the page
    theme::set( 'site', $site );
    theme::set( 'page', $page );
    if( c::get( 'debug' )) theme::set( 'error', error::render( TRUE ));

    $html = theme::load( $page->template(), $page->render(), TRUE );

    // OUTPUT TO BROWSER
    echo $html;

    exit;
  }

}

?>
