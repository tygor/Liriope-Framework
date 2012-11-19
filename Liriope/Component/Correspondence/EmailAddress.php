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
    $this->setAddress(trim($address));
    $this->setName(trim($name));
  }

  /**
   * TO STRING
   */
  public function __toString() {
    return $this->get();
  }

  /**
   * Returns a string name and email pair
   *
   * @return string The "name <address>" string
   */
  public function get() {
    if(empty($this->name)) {
      return $this->address ?: '';
    }

    $format = '%s <%s>';
    $return = sprintf($format, $this->name, $this->address);
    return $return;
  }

  /**
   * Stores a name
   *
   * @return void
   */
  private function setName($name) {
    $this->name = $name;
  }

  /**
   * Gets the stored name
   *
   * @return string The stored name
   */
  public function getName() {
    return $this->name ?: FALSE;
  }

  /**
   * Stores a address
   *
   * @return void
   */
  private function setAddress($address) {
    $address = $this->validateEmail($address) ? $address : FALSE;
    $this->address = $address;
  }

  /**
   * Gets the stored address
   *
   * @return string The stored email address
   */
  public function getAddress() {
    return $this->address ?: FALSE;
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
