<?php
/* --------------------------------------------------
 * theme.class.php
 * --------------------------------------------------
 *
 */

// Direct access protection
if( !defined( 'LIRIOPE' )) die( 'Direct access is not allowed.' );

class theme extends page {

  static public $_content; 
  static protected $name;

  static function start( $theme='grass' ) {
    self::$name = $theme; 

    // toggle for Development
    if( c::get( 'development' ))
    {
      self::addStylesheet( self::folder() . '/style.css' );
      self::addStylesheet( self::folder() . '/style.less', 'stylesheet/less' );
      self::addScript( 'js/libs/less-1.3.0.min.js' );
      self::addScriptBlock( 'less.watch();' );
    } else {
      self::addStylesheet( self::folder() . '/style.css' );
    }
  }

  static function folder() {
    return 'themes/' . self::$name;
  }

  static function render( $file=NULL, $vars=array(), $dump=FALSE ) {
    if( $file===NULL ) $file = 'index.php';
    // try the site theme folder
    $load = load::exists( $file, c::get( 'root.theme' ) . '/' . self::$name );
    // or, throw an error
    if( !$load ) {
      error::report( array(
        'line' => __LINE__,
        'function' => __METHOD__,
        'class' => __CLASS__,
        'message' => 'Couldn\'t find that theme file.'
      ));
      return;
    }
    self::set( $vars );
    self::set( '_content', self::$_content );
    return self::renderFile( $load, $vars, $dump );
  }

}

