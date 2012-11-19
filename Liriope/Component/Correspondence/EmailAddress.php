<?php

namespace Liriope\Component\Correspondence;

/**
 * This email address class handles, well, handles email addresses
 */

class EmailAddress {

  // @var string
  private $name;
  // @var string
  private $address;
  // @var boolean
  private $valid = FALSE;

  /**
   * CONSTRUCTOR
   */
  public function __construct($address, $name=NULL) {
    $this->storeAddress($address);
    $this->storeName($name);
  }

  /**
   * TO STRING
   */
  public function __toString() {
    $format = "%s <%s>";
    if(!isset($this->name)) return $this->address ?: '';
    return sprintf($format, $this->name, $this->address);
  }

  /**
   * Stores a name
   *
   * @return void
   */
  private function storeName($name) {
    $this->name = $name;
  }

  /**
   * Stores a address
   *
   * @return void
   */
  private function storeAddress($address) {
    $address = $this->validateEmail($address) ? $address : FALSE;
    $this->address = $address;
  }

  /**
   * Validates an email address
   *
   * @param string $address The address to check
   *
   * @return mixed Returns validated $address, or FALSE if the filter fails
   */
  private function validateEmail($address) {
    $address = filter_var(trim((string) $address), FILTER_VALIDATE_EMAIL);
    $this->valid = $address ? TRUE : FALSE;
    return $address;
  }

  /**
   * Tells if the email address is valid or not
   */
  public function isValid() {
    return $this->valid;
  }

}
