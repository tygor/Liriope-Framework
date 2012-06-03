<?php
//
// Cache object
// cache.class.php
// for handling caching
//

// Direct access protection
if( !defined( 'LIRIOPE' )) die( 'Direct access is not allowed.' );

class cache {

  // file()
  //
  static function file( $file ) {
    return c::get( 'root.cache' ) . "/$file";
  }

  // set()
  //
  static function set( $file, $content, $raw=FALSE ) {
    if( !c::get( 'cache' )) return FALSE;
    if( $raw == FALSE ) $content = @serialize( $content );
    if( $content ) {
      f::write( self::file( $file ), $content );
    }
  }

  // get()
  //
  static function get( $file, $raw=FALSE, $expires=FALSE ) {
    if( !c::get( 'cache' )) return FALSE;
    if( $expires && self::expired( $file, $expires )) return FALSE;
    $content = f::read( self::file( $file ));
    if( $raw == FALSE ) $content = $unserialize( $content );
    return $content;
  }

  // remove()
  //
  static function remove( $file ) {
    f::remove( self::file( $file ));
  }

  // flush()
  //
  static function flush() {
    $root = c::get( 'root.cache' );
    if( !is_dir( $root )) return $root;
    dir::clean( $root );
  }

  // modified()
  //
  static function modified( $file ) {
    if( !c::get( 'cache' )) return FALSE;
    return @filectime( self::file( $file ));
  }

  // expired()
  //
  static function expired( $file, $time=FALSE ) {
    return (cache::modified( $file ) < time() - $time ) ? TRUE : FALSE;
  }

}
?>
