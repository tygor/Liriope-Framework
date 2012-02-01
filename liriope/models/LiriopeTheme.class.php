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
  var $themeCheck;
  var $homepageFlag = FALSE;
  var $variables = array();
  var $stylesheets = array();
  var $scripts = array();

  function __construct()
  {
    // this function is overridden by extending classes
    // use $this->start() instead
    $path = realpath( c::get( 'root.liriope') . '/views/theme' );
    $this->set( 'theme.path', $path );
    self::start();
  }

  function start()
  {
    $this->themeCheck = FALSE;

    $path = $this->get( 'path' );
    if( empty( $path ))
    {
      $path = realpath( c::get( 'root') . '/application/views/theme' );
      $this->set( 'theme.path', $path );
    }

    // set default values
    $this->set( 'theme.name', 'Default' );
    $this->set( 'theme.folder', 'default' );
    $this->set( 'theme.file', 'default.php' );
    $this->set( 'page.title', 'Liriope : Monkey Grass' );
    $this->set( 'page.DOCTYPE', '<!DOCTYPE html>' );

    // add theme stylesheets
    $this->addStylesheet( 'style.css' );
    $this->addStylesheet( 'style.less', 'stylesheet/less' );

    // initialize the content variable
    $this->_content = "";
  }

  public function setHomePage( $bool )
  {
    $this->homepageFlag = $bool;
  }

  public function isHomePage()
  {
    return $this->homepageFlag;
  }

  public function getPathToTheme()
  {
    $path = $this->get( 'theme.path' );
    $path .= '/' . $this->get( 'theme.folder' );
    $path .= '/' . $this->get( 'theme.file' );
    return $path;
  }

  private function checkTheme()
  {
    $name =   $this->get( 'theme.name' );
    $folder = $this->get( 'theme.folder' );
    $file =   $this->get( 'theme.file' );
    if( !empty( $name ) && empty( $folder ))
    {
      $folder = strtolower( $name );
      $this->set( 'theme.folder', $folder );
    }
    if( !empty( $name ) && empty( $file ))
    {
      $file = strtolower( $name );
      $this->set( 'theme.file', "$file.php" );
    }
    if( !empty( $name ) && !empty( $folder ) && !empty( $file ))
    {
      $this->themeCheck = true;
      return true;
    }
    return false;
  }

  public function save_content( $html )
  {
    $this->_content .= $html;
  }

  public function get_content()
  {
    echo $this->_content;
  }

  public function addStylesheet( $file=NULL, $rel='stylesheet' )
  {
    if( empty( $file )) return false;
    $this->stylesheets[] = array( 'file' => $file, 'rel' => $rel );
  }

  public function getStylesheets()
  {
    $string = "";
    foreach( $this->stylesheets as $css )
    {
      $format = '<link rel="%s" href="%s">';
      $string .= sprintf( $format, $css['rel'], 'css/' . $css['file'] ) . "\n";
    }
    return $string;
  }

  public function set( $key, $value )
  {
    $this->variables[$key] = $value;
  }

  public function get( $key )
  {
    if( !isset( $this->variables[$key] )) return false;
    return $this->variables[$key];
  }

  public function render() {
    if( !$this->checkTheme() ) throw new Exception( "Your theme " . __METHOD__ . " is not ready to be rendered." );
    $file = $this->getPathToTheme();
    include( $file );
  }
}

