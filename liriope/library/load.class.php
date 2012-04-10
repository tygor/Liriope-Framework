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

  static function autoload( $className ) {
    // Apply the naming convention
    $className = ucfirst( $className ) . '.class.php';

    // find out if the file exists
    try {
      if( !self::seek( $className )) throw new Exception( 'Unable to find the ' . $className . ' object with the autoloader.' );
    } catch( Exception $e ) {
        header("HTTP/1.0 500 Internal Server Error");
        echo $e->getMessage();
        echo "<hr><pre>";
        echo $e->getTraceAsString();
        echo "</pre><hr>";
        exit;
    }
    return true; 
  }

  static function lib()
  {
    $root = c::get( 'root.liriope' );
    require_once( $root . '/library/uri.class.php' );
    require_once( $root . '/library/router.class.php' );
    require_once( $root . '/library/LiriopePage.class.php' );
    require_once( $root . '/library/theme.class.php' );
    require_once( $root . '/library/browser.class.php' );
    require_once( $root . '/library/error.class.php' );
    require_once( $root . '/library/filter.class.php' );
    require_once( $root . '/controllers/LiriopeController.class.php' );
  }

  static function models()
  {
    $root = c::get( 'root.liriope' );
    require_once( $root . '/models/SQLQuery.class.php' );
    require_once( $root . '/models/LiriopeModel.class.php' );
    require_once( $root . '/models/LiriopeView.class.php' );
    require_once( $root . '/models/LiriopeRequest.class.php' );
    require_once( $root . '/models/XmlModel.class.php' );
  }

  static function themes()
  {
    $root = c::get( 'root.liriope' );
    require_once( $root . '/models/LiriopeTheme.class.php' );
    require_once( $root . '/models/GrassTheme.class.php' );
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

  static function file( $file=NULL, $require=FALSE )
  {
    if( !file_exists( $file )) return false;
    if( $require ) require_once( $file );
    else include( $file );
    return true;
  }

  static function seek( $file=NULL )
  {
    if( $file===NULL ) return false;
    if( $file = self::exists( $file )) {
      self::file( $file, TRUE );
      return true; 
    }
    return false;
  }

  static function exists( $file=NULL, $searchPath=NULL )
  {
    if( empty( $file )) return false;
    
    $paths = c::get( 'path' );

    // TODO: this custom path option isn't working
    // it simply does nothing at the moment
    if( !empty( $searchPath ))
    {
      if( is_array( $searchPath )) {
        foreach( $searchPath as $newPath ) {
          $path = array_unshift( $paths, $newPath );
        }
      } else {
        $path = array_unshift( $paths, $searchPath );
      }
    }

    foreach( $paths as $path ) { 
      if( file_exists( "$path/$file" )) return "$path/$file"; 
    } 

    return false;
  }

}

?>
