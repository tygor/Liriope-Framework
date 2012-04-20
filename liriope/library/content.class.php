<?php
//
// Content object
// content.class.php
// handles output buffering
//

// Direct access protection
if( !defined( 'LIRIOPE' )) die( 'Direct access is not allowed.' );

class content {

  //
  // start()
  // starts the output buffer
  //
  static function start() {
    ob_start();
  }

  //
  // end( $return )
  // stops the output buffer with and option to return the content
  // as a string or flush it
  //
  // @param  boolean $return TRUE to return the content as a string, FALSE to flush it
  // @return mixed
  //
  static function end( $return=FALSE ) {
    if( $return ) {
      $content = ob_get_contents();
      ob_end_clean();
      return $content;
    } 
    ob_end_flush();
  }

  //
  // get( $file, $return )
  // loads the content from the passed file
  //
  // @param  string  $file The path to the file
  // @param  boolean TRUE returns the content as a string, FALSE echos it
  // @return mixed
  static function get( $file, $return=TRUE ) {
    if( empty( $file )) trigger_error( 'The file passed is empty', E_USER_ERROR );

    self::start();
    require_once( $file );
    $content = self::end( TRUE );
    if( $return ) return $content;
    echo $content;
  }

}

?>
