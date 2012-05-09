<?php

// Direct access protection
if( !defined( 'LIRIOPE' )) die( 'Direct access is not allowed.' );

//
// error.php
// collects and displays issues and errors
//

class error {
  static $_ = array();

  public static function report( $params=array() ) {
    if( empty( $params ) || !is_array( $params )) return;
    self::$_[] = $params;
  }

  public static function dump() {
    return self::$_;
  }

  static function set( $id=NULL, $val=NULL ) {
    if( $id === NULL ) return false;
    self::$_[$id] = $val;
  }

  static function get( $id=NULL, $default=NULL ) {
    if( $id === NULL ) return self::$_;
    return self::$_[$id] ? self::$_[$id] : $default ;
  }

  static function render( $return=FALSE ) {
    $content = content::get( c::get( 'root.liriope' ) . '/views/errors/debugging.php', NULL, TRUE );
    if( $return ) return $content;
    echo $content;
  }

  static function handler( $code, $msg, $file, $line ) {
    if( !( error_reporting() & $code )) {
      // Error code not included in error_reporting
      return;
    }

    switch( $code ) {
      case 256:
      case E_USER_ERROR:
        echo "<b>Liriope Error</b> [$code]<br>\n";
        echo "<h1>FATAL ERROR: $msg</h1>\n";
        echo "<ul>\n";
        echo "<li>Line: $line</li>\n";
        echo "<li>File: $file</li>\n";
        echo "<li>PHP version: " . PHP_VERSION . "</li>\n";
        echo "<li>Operating system: " . PHP_OS . "</li>\n";
        echo "</ul>\n";
        echo "  <hr><h2>BACKTRACE</h2>\n";
        $stack = debug_backtrace();
        array_shift( $stack );
        foreach( $stack as $k => $v ) {
          echo "<b>--- Step #$k:</b><br>\n";
          a::show( $v );
        }
        echo "  <hr>\n";
        echo "  Aborting&hellip;<br>\n";
        exit(1);
        break;
      case 512:
      case E_USER_WARNING:
        self::report( array(
          'code' => $code,
          'codeString' => "WARNING",
          'msg'  => $msg,
          'file' => $file,
          'line' => $line
        ));
        break;
      case 1024:
      case E_USER_NOTICE:
        self::report( array(
          'code' => $code,
          'codeString' => "NOTICE",
          'msg'  => $msg,
          'file' => $file,
          'line' => $line
        ));
        break;
      default:
        echo "<b>Unknown error type</b>: [$code] $msg<br>\n";
        echo "<em>($file) line: $line</em><br>\n";
        break;
    }

    // don't execute PHP internal error handler
    return true;
  }
}

