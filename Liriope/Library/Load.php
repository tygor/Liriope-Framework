<?php

// Direct access protection
if( !defined( 'LIRIOPE' )) die( 'Direct access is not allowed.' );

//
// --------------------------------------------------
// Loading class
// --------------------------------------------------
//

class load {

  static function autoload( $className ) {
    // Apply the naming convention
    $className = ucfirst( $className ) . '.class.php';

    self::seek( $className );

    /*
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
    */
  }

  static function lib() {
    // load controllers, model, helpers, or just anything that
    // is integral to the Liriope Framework
    $root = c::get( 'root.liriope' );
    // Toolbox
    load::file( $root . '/Library/String.php', TRUE );
    load::file( $root . '/Library/Array.php', TRUE );
    load::file( $root . '/Library/Server.php', TRUE );
    load::file( $root . '/Library/Uri.php', TRUE );
    load::file( $root . '/Library/Router.php', TRUE );
    load::file( $root . '/Library/Files.php', TRUE );
    load::file( $root . '/Library/Filter.php', TRUE );
    load::file( $root . '/Library/Tools.php', TRUE );
    load::file( $root . '/Library/Search.php', TRUE );
    load::file( $root . '/Library/Content.php', TRUE );
    load::file( $root . '/Library/Error.php', TRUE );
    load::file( $root . '/Library/Dir.php', TRUE );
    // 
    // load::file( $root . '/library/request.class.php', TRUE );
    // load::file( $root . '/library/menu.class.php', TRUE );
    // load::file( $root . '/library/counter.class.php', TRUE );
    // load::file( $root . '/controllers/LiriopeController.class.php', TRUE );
  }

  static function config() {
    // load configuration and default variables
    load::file( c::get( 'root.liriope' ) . '/defaults.php' );
    load::file( c::get( 'root.application' ) . '/defaults.php' );
  }

  static function models() {
    // load models related to the MVC
    $root = c::get( 'root.liriope' );
    load::file( $root . '/models/View.class.php', TRUE );
    load::file( $root . '/models/Theme.class.php', TRUE );
    load::file( $root . '/models/Page.class.php', TRUE );
    load::file( $root . '/models/SQLQuery.class.php', TRUE );
    load::file( $root . '/models/Xml.class.php', TRUE );
    // load::file( $root . '/models/Files.class.php', TRUE );
  }

  static function helpers() {
    // load helpers and tools
    $root = c::get( 'root.liriope' );
    load::file( $root . '/library/tools.class.php', TRUE );
    load::file( $root . '/Library/Helpers.php', TRUE );
  }

  static function plugins() {
    // load plugins
    $root = c::get( 'root.liriope' );
    load::file( $root . '/plugins/spyc.php', TRUE );
  }

  //
  // file()
  // includes the passed full path to a file
  //
  // @param  string  $file The full path to a file to be included
  // @param  bool    $require TURE requires the file, FALSE inlucdes it
  // @return bool    TRUE on success, or FALSE
  //
  static function file( $file=NULL, $require=FALSE, $params=NULL ) {
    // inlude the global page file so that snippets can see it
    if( !file_exists( $file )) return false;
    @extract( $params );
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
  static function seek( $file=NULL ) {
    if( $file===NULL ) return false;
    if( $file = self::exists( $file )) {
      self::file( $file, TRUE );
      return true; 
    }
    return false;
  }

  // exists()
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
        foreach( c::get('load.filetypes', array( 'php', 'html', 'htm', 'txt', 'yml', 'yaml' )) as $ext ) {
          if( file_exists( "$path/$file.$ext" ) && !is_dir( "$path/$file.$ext" )) return "$path/$file.$ext"; 
        }
      }
      if( file_exists( "$path/$file" ) && !is_dir( "$path/$file" )) return "$path/$file"; 
    } 

    return false;
  }

}