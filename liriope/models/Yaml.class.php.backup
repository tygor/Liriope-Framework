<?php

use Liriope\Toolbox\a;

// Direct access protection
if( !defined( 'LIRIOPE' )) die( 'Direct access is not allowed.' );

class Yaml {
  var $yaml;

  function __construct( $yaml=NULL ) {
    if( $yaml===NULL ) return false;
    $this->yaml = $yaml;
  }

  // get()
  // @param  string $v the value of the Yaml to grab OR return the whole Yaml
  // 
  public function get( $v=FALSE, $toArray=FALSE ) {
    if( $v===FALSE ) {
      $yaml = $this->parse();
    }

    if( $toArray ) return $yaml;
    return a::toObject( $yaml );
  }

  private function removeDashes( $yaml ) {
    $test = key( a::rewind( $yaml ));
    if( substr( $test, 0, 3 ) === '---' ) array_shift( $yaml );
    if( end($yaml) === '...' ) array_pop( $yaml );
    return $yaml;
  }

  public function parse() {
    if( empty( $this->yaml )) return FALSE;
    if( is_file( $this->yaml )) {
      $yaml = SPyc::YAMLLoad( $this->yaml );
    } else {
      $yaml = Spyc::YAMLLoadString( $this->yaml );
    }
    $yaml = $this->removeDashes( $yaml );
    return $yaml;
  }
  
}

?>
