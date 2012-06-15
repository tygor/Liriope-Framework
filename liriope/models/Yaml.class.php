<?php

// Direct access protection
if( !defined( 'LIRIOPE' )) die( 'Direct access is not allowed.' );

class Yaml {
  var $yaml;

  function __construct( $yaml=NULL ) {
    if( $yaml===NULL ) return false;
    $this->yaml = $yaml;
  }

  public function get( $v=FALSE ) {
    if( $v===FALSE ) return $this->parse();
  }

  public function parse() {
    if( empty( $this->yaml )) return FALSE;
    return Spyc::YAMLLoadString( $this->yaml );
  }
  
}

?>
