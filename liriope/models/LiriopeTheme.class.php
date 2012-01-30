<?php
/**
 * LiriopeTheme.class.php
 * --------------------------------------------------
 * 
 * A controller specifically for the chrome or theme or template
 * of the site.
 */

// Direct access protection
if( !defined( 'LIRIOPE' )) die( 'Direct access is not allowed.' );

class LiriopeTheme {

  var $_content;
  var $_page;
  var $themeFile;
  var $variables = array();
  var $stylesheets = array();
  var $scripts = array();

  function __construct()
  {
    self::start();
  }

  function start()
  {
    // set default values
    $this->set( 'name', 'Default Theme' );
    $this->set( 'DOCTYPE', '<!DOCTYPE html>' );
    $this->setThemeFile( 'default.php' );

    // initialize the content variable
    $this->_content = "";
  }

  public function setThemeFile( $file=NULL )
  {
    $file = realpath( dirname(__FILE__) . "/../views/theme/$file" );
    if( file_exists( $file ))
    {
      $this->themeFile = $file;
    }
  }

  public function getThemeFile()
  {
    if( !isset( $this->themeFile )) return false;
    return $this->themeFile;
  }

  public function save_content( $html )
  {
    $this->_content .= $html;
  }

  public function get_content()
  {
    echo $this->_content;
  }

  public function addStylesheet( $file=NULL )
  {
  }

  public function set( $key, $value )
  {
    $this->variables[$key] = $value;
  }

  public function get( $key )
  {
    return $this->variables[$key];
  }

  public function render() {
    $file = $this->getThemeFile();
    include( $this->getThemeFile() );
  }
}

