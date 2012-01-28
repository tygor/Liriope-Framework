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

  public function address( $getVars=NULL )
  {
    $xml = new XmlModel();
    $xml->setFile( 'data/contact-info.xml' );
    $this->_page->set( 'c', $xml->get()->contact[0] );
  }

  public function contact_form( $getVars=NULL )
  {
  }
}

