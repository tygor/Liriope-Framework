<?php

namespace Liriope\Component;

//
// --------------------------------------------------
// Loading class
// --------------------------------------------------
//

class Load {

  static function lib() {
    // load controllers, model, helpers, or just anything that
    // is integral to the Liriope Framework
    $root = \c::get( 'root.liriope' );
    // Toolbox
    self::file( $root . '/library/search.class.php', TRUE );
    // self::file( $root . '/controllers/LiriopeController.php', TRUE );
  }

  static function config() {
    // load configuration and default variables
    self::file( \c::get( 'root.liriope' ) . '/configuration.php' );
    self::file( \c::get( 'root.application' ) . '/defaults.php' );
  }

  static function models() {
    // load models related to the MVC
    $root = \c::get( 'root.liriope' );
    self::file( $root . '/models/SQLQuery.class.php', TRUE );
    self::file( $root . '/models/Xml.class.php', TRUE );
    self::file( $root . '/models/Files.class.php', TRUE );
  }

  static function helpers() {
    // load helpers and tools
    $root = \c::get( 'root.liriope' );
    self::file( $root . '/library/tools.class.php', TRUE );
    self::file( $root . '/Helpers/Liriope.php', TRUE );
    self::file( $root . '/Helpers/Theme.php', TRUE);
  }

  static function plugins() {
    // load plugins
    $root = \c::get( 'root.liriope' );
    // self::file( $root . '/plugins/spyc.php', TRUE );
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
  // Looks for the passed file using the exists() function
  // which returns the full path plus file name, and then
  // includes it using the file() function
  //
  // (string) $file  the file to be sought
  // returns  (bool) TRUE on success, or FALSE
  //
  static function seek( $file=NULL ) {
    if( $file===NULL ) return false;
    if( $file = self::exists( $file )) {
      self::file( $file, TRUE );
      return $file; 
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
  // returns  (string)    discovered path/filename on SUCCESS, or FALSE
  // 
  static function exists( $file=NULL, $searchPath=NULL )
  {
    if( empty( $file )) return false;
    
    // grab the default configuration paths
    $paths = \c::get( 'path' );

    // get as much info from the passed $file as possible
    $info = pathinfo( $file );
    
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
      // TODO: move this logic outside of the loop since you won't need to check the file every cycle.
      if( !isset( $info['extension'] )) {
        foreach( \c::get('load.filetypes',
                 array( 'php', 'html', 'htm', 'txt', 'yml', 'yaml' ))
                 as $ext ) {
          if( file_exists( "$path/$file.$ext" ) && !is_dir( "$path/$file.$ext" )) {
            return "$path/$file.$ext"; 
          }
        }
      }
      if( file_exists( "$path/$file" ) && !is_dir( "$path/$file" )) return "$path/$file"; 
    } 

    return false;
  }

}
