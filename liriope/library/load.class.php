<?php

// Direct access protection
if( !defined( 'LIRIOPE' )) die( 'Direct access is not allowed.' );

/*
 * --------------------------------------------------
 * Loading class
 * --------------------------------------------------
 */

class load 
{

  static function lib()
  {
    $root = c::get( 'root.liriope' );
    require_once( $root . '/library/router.class.php' );
    require_once( $root . '/library/LiriopeController.class.php' );
    require_once( $root . '/library/LiriopeModel.class.php' );
    require_once( $root . '/library/LiriopeTheme.class.php' );
    require_once( $root . '/library/LiriopeView.class.php' );
  }

  static function models()
  {
    $root = c::get( 'root.liriope' );
    require_once( $root . '/library/XmlModel.class.php' );
    require_once( $root . '/library/SQLQuery.class.php' );
    require_once( $root . '/library/LiriopeRequest.class.php' );
  }

  static function tools()
  {
    $root = c::get( 'root.liriope' );
    require_once( $root . '/library/tools.class.php' );
  }

  static function helpers()
  {
    $root = c::get( 'root.liriope' );
    require_once( $root . '/library/LiriopeHelpers.php' );
  }

  static function file( $file=NULL )
  {
    if( !file_exists( $file )) return false;
    require_once( $file );
    return true;
  }

  static function seek( $file=NULL )
  {
    if( empty( $file )) return false;
    if( $file = self::exists( $file )) {
      self::file( $file );
      return true; 
    }
    return false;
  }

  static function exists( $file=NULL )
  {
    if( empty( $file )) return false;

    $paths = c::get( 'path' );

    foreach( $paths as $path ) { 
      if( file_exists( "$path/$file" )) return "$path/$file"; 
    } 

    return false;
  }

}

?>
