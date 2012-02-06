<?php
/* --------------------------------------------------
 * theme.class.php
 * --------------------------------------------------
 * A controller specifically for the chrome or theme or template
 * of the site.
 *
 */

// Direct access protection
if( !defined( 'LIRIOPE' )) die( 'Direct access is not allowed.' );

class theme extends page {

  static public $_content; 

  static public $vars    = array();
  static public $css     = array();
  static public $scripts = array();

  static function start()
  {
    self::set( 'theme.check', FALSE );

    $path = realpath( dirname( __FILE__ ) . '/../views/theme' );
    self::set( 'theme.path', $path );

    // set default values
    self::set( 'theme.name', 'Default' );
    self::set( 'theme.folder', 'default' );
    self::set( 'theme.file', 'default.php' );
    self::set( 'page.title', 'Liriope : Monkey Grass' );
    self::set( 'page.DOCTYPE', '<!DOCTYPE html>' );

    // add theme stylesheets
    self::addStylesheet( 'style.css' );
    self::addStylesheet( 'style.less', 'stylesheet/less' );

    // initialize the content variable
    self::set( '_content', '' );
  }

  public function getPath()
  {
    $path = self::get( 'theme.path' );
    $path .= '/' . self::get( 'theme.folder' );
    $path .= '/' . self::get( 'theme.file' );
    return $path;
  }

  private function checkTheme()
  {
    $name =   self::get( 'theme.name' );
    $folder = self::get( 'theme.folder' );
    $file =   self::get( 'theme.file' );
    if( !empty( $name ) && empty( $folder )) {
      $folder = strtolower( $name );
      self::set( 'theme.folder', $folder );
    }
    if( !empty( $name ) && empty( $file )) {
      $file = strtolower( $name );
      self::set( 'theme.file', "$file.php" );
    }
    if( !empty( $name ) && !empty( $folder ) && !empty( $file )) {
      self::set( 'theme.check', TRUE );
      return true;
    }
    return false;
  }

  static function save_content( $html ) {
    self::$_content .= $html;
  }

  public function get_content() {
    echo self::$_content;
  }

  static function addStylesheet( $file=NULL, $rel='stylesheet' ) {
    if( $file===NULL ) return false;
    self::$css[] = array( 'file' => $file, 'rel' => $rel );
  }

  static function getStylesheets() {
    return (array)self::$css;
    $string = '';
    foreach( self::$css as $css )
    {
      $format = '<link rel="%s" href="%s">';
      $string .= sprintf( $format, $css['rel'], 'css/' . $css['file'] ) . "\n";
    }
    return $string;
  }

  static function render( $vars = array(), $dump = FALSE ) {
    if( !self::checkTheme() ) {
      throw new Exception( "Your theme " . __METHOD__ . " is not ready to be rendered." );
    }

    $file = self::getPath();

    extract( self::$vars );
    extract( $vars );
    
    ob_start();

    require( $file );

    if( $dump ) {
      $content = ob_get_contents();
      ob_end_clean();
      return $content;
    }
    ob_end_flush();
  }
  
  static function renderFile( $file, $vars=array(), $return=FALSE ) {
    if( !file_exists( $file )) return false;
    @extract( self::$vars );
    @extract( $vars );
    self::startBuffer();
    require( $file );
    return self::endBuffer( $return );
  }

}

