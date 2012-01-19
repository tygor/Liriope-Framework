<?php
/**
 * AboutusController.class.php
 */

class AboutusController Extends LiriopeController {
  public function show( $getVars=NULL )
  {
  }

  public function handshake( $getVars=NULL )
  {
    $this->_template->set( 'whichhand', 'left' );
  }
}

