<?php
/**
 * ContactusController.class.php
 */

class ContactusController Extends LiriopeController {
  public function show( $getVars=NULL )
  {
    // Email
    $this->_template->set( 'email', 'info@liriope.local');
    // Phone
    $this->_template->set( 'phone', '803-123-4567');
    // Address
    $this->_template->set( 'street', '1234 Main Street');
    $this->_template->set( 'postofficebox', 'P.O. Box 1234');
    $this->_template->set( 'city', 'Rock Hill');
    $this->_template->set( 'state', 'SC');
    $this->_template->set( 'zip', '29730');
  }

  public function address( $getVars=NULL )
  {
    // Address
    $this->_template->set( 'street', '1234 Main Street');
    $this->_template->set( 'postofficebox', 'P.O. Box 1234');
    $this->_template->set( 'city', 'Rock Hill');
    $this->_template->set( 'state', 'SC');
    $this->_template->set( 'zip', '29730');
  }

  public function contact_form( $getVars=NULL )
  {
  }
}

