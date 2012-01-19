<?php
/**
 * ContactusController.class.php
 */

class ContactusController Extends LiriopeController {
  public function show( $getVars=NULL )
  {
    // Email
    $this->_template->set( 'email', 'office@jmcope.com');
    // Phone
    $this->_template->set( 'phone', '803-329-3250');
    // Address
    $this->_template->set( 'street', '1069 Bayshore Drive');
    $this->_template->set( 'postofficebox', 'P.O. Box 4047');
    $this->_template->set( 'city', 'Rock Hill');
    $this->_template->set( 'state', 'SC');
    $this->_template->set( 'zip', '29732');
  }

  public function address( $getVars=NULL )
  {
    // Address
    $this->_template->set( 'street', '1069 Bayshore Drive');
    $this->_template->set( 'postofficebox', 'P.O. Box 4047');
    $this->_template->set( 'city', 'Rock Hill');
    $this->_template->set( 'state', 'SC');
    $this->_template->set( 'zip', '29732');
  }

  public function contact_form( $getVars=NULL )
  {
  }
}

