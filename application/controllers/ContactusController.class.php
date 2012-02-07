<?php
/**
 * ContactusController.class.php
 */

class ContactusController Extends LiriopeController {
  public function show( $getVars=NULL )
  {
    $xml = new XmlModel();
    $xml->setFile( 'data/contact-info.xml' );
    $this->_page->set( 'contacts', $xml->get() );
  }
}

