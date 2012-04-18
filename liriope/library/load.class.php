<?php

// Direct access protection
if( !defined( 'LIRIOPE' )) die( 'Direct access is not allowed.' );

//
// --------------------------------------------------
// Loading class
// --------------------------------------------------
//

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
    require_once( $root . '/library/obj.class.php' );
    require_once( $root . '/library/uri.class.php' );
    require_once( $root . '/library/router.class.php' );
    require_once( $root . '/library/browser.class.php' );
    require_once( $root . '/library/error.class.php' );
    require_once( $root . '/library/filter.class.php' );
    require_once( $root . '/library/content.class.php' );
    require_once( $root . '/library/server.class.php' );
    require_once( $root . '/library/array.class.php' );
    require_once( $root . '/controllers/LiriopeController.class.php' );
  }

  static function config() {
    include( c::get( 'root.liriope' ) . '/defaults.php' );
    include( c::get( 'root.application' ) . '/defaults.php' );
  }

  static function models()
  {
    $root = c::get( 'root.liriope' );
    require_once( $root . '/models/View.class.php' );
    require_once( $root . '/models/Page.class.php' );
    require_once( $root . '/library/theme.class.php' );
    require_once( $root . '/models/SQLQuery.class.php' );
    require_once( $root . '/models/Xml.class.php' );
    require_once( $root . '/models/Files.class.php' );
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

  //
  // file( $file, $require )
  // includes the passed full path to a file
  //
  // (string) $file    the full path to a file to be included
  // (bool)   $require TURE requires the file, FALSE inlucdes it
  // returns  (bool) TRUE on success, or FALSE
  //
  static function file( $file=NULL, $require=FALSE )
  {
    if( !file_exists( $file )) return false;
    if( $require ) require_once( $file );
    else include( $file );
    return true;
  }

  //
  // seek( $file )
  // looks for the passed file using the exists() function
  // which returns the full path plus file name, and then
  // includes it using the file() function
  //
  // (string) $file the file to be sought
  // returns  (bool) TRUE on success, or FALSE
  //
  static function seek( $file=NULL )
  {
    if( $file===NULL ) return false;
    if( $file = self::exists( $file )) {
      self::file( $file, TRUE );
      return true; 
    }
    return false;
  }

  //
  // exists( $file, $searchPath )
  // looks for the given $file using the configuration path
  // plus any additional $searchPath locations, parsing through
  // the additional paths first
  //
  // (string) $file       the file to be sought
  // (array)  $searchPath additional locations to seek
  // returns  (string) discovered path/filename on SUCCESS, or FALSE
  // 
  static function exists( $file=NULL, $searchPath=NULL )
  {
    if( empty( $file )) return false;
    
    // grab the default configuration paths
    $paths = c::get( 'path' );

    // get as much info from the passed $file as possible
    $info = pathinfo( $file );
    //$file = $info['basename'];
    
    if( !empty( $searchPath )) {
      if( is_array( $searchPath )) {
        foreach( $searchPath as $newPath ) {
          array_unshift( $paths, $newPath );
          if( $info['dirname'] && $info['dirname'] !== '.' ) {
            array_unshift( $paths, $newPath . '/' . $info['dirname'] );
          }
        }
      } else {
        array_unshift( $paths, $searchPath );
        if( $info['dirname'] && $info['dirname'] !== '.' ) {
          array_unshift( $paths, $searchPath . '/' . $info['dirname'] );
        }
      }
    }

    foreach( $paths as $path ) { 
      // but what if the file passed has no extension?
      if( !isset( $info['extension'] )) {
        foreach( c::get( 'content.filetypes', array( 'php', 'html', 'htm', 'txt' )) as $ext ) {
          if( file_exists( "$path/$file.$ext" ) && !is_dir( "$path/$file.$ext" )) return "$path/$file.$ext"; 
        }
      }
      if( file_exists( "$path/$file" ) && !is_dir( "$path/$file" )) return "$path/$file"; 
    } 

    return false;
  }

}

?>
