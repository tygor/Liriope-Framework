<?php
//
// File object
// file.class.php
// for handling files
//

// Direct access protection
if( !defined( 'LIRIOPE' )) die( 'Direct access is not allowed.' );

class f {

  // write()
  // Creates a new file
  // 
  // @param  string  $file The path for the new file
  // @param  mixed   $content Either a string or an array. Arrays will be converted to JSON. 
  // @param  bool    $append true: append the content to an exisiting file if available. false: overwrite. 
  // @return bool    
  //   
  static function write($file,$content,$append=false){
    if( is_array( $content )) $content = json_encode( $content );
    $mode = ( $append ) ? FILE_APPEND : false;
    $write = file_put_contents( $file, $content, $mode );
    @chmod( $file, 0666 );
    return $write;
  }

  // read()
  // Reads the content of a file
  // 
  // @param  string  $file The path for the file
  // @param  mixed   $parse The str object parse method to use
  // @return mixed 
  //   
  static function read( $file, $parse=FALSE ) {
    $content = @file_get_contents( $file );
    return ( $parse) ? str::parse( $content, $parse ) : $content;
  }

  // remove()
  // Deletes a file
  //
  // @param  string  $file The path for the file
  // @return boolean 
  //  
  static function remove($file) {
    return (file_exists($file) && is_file($file) && !empty($file)) ? @unlink($file) : false;
  }
}

?>
