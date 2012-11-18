<?php

namespace Liriope\Component\Correspondence;

/**
 * This email class handles sending PHP email using the mail() function
 */

// TODO: Implement CC, and BCC
// TODO: Implement naming the email addresses with a user name ex. Tyler <tyler@host.com>

class Email {

  // @var array
  private $headers = array();
  // @var string
  private $from;
  // @var array
  private $to = array();
  // @var string
  private $subject;
  // @var string
  private $message;
  // @var string
  private $replyTo;

  /**
   * CONSTRUCTOR
   */
  public function __construct($to='', $from='', $subject='', $body='') {
    // The assumption is that this email is an HTML email, so the Content-Type header must be set
    $this->headers['mime'] = 'MIME-Version: 1.0';
    $this->headers['type'] = 'Content-type: text/html; charset=UTF-8';

    $this->sendTo($to);
    $this->sendFrom($from);
    $this->subject($subject);
    $this->message($body);
  }

  /**
   * Gets the TO: address(es)
   *
   * @return string The email address(es) to send the message to
   */
  public function getTo() {
    foreach($this->to as $key => $to) {
      $this->to[$key] = trim($to);
    }

    $to = implode(', ', $this->to);
    if(empty($to)) throw new \Exception('Your message needs at least one address as the destination');

    return $to;
  }

  /**
   * Adds to the list of address to receive the email
   *
   * This method implements a fluent interface.
   *
   * @param mixed $address The string or array of string email addresses to get the email
   *
   * @return Email The current Email instance
   */
  public function sendTo($address) {
    foreach((array) $address as $a) {
      if($this->validateEmail($a)) {
        $this->to[] = $a;
        $this->to = array_flip(array_flip($this->to));
      }
    }

    return $this;
  }

  /**
   * Sets the address the email is coming from
   *
   * This method implements a fluent interface.
   *
   * @param string $address The string email addresses for the 'from' field of the email
   *
   * @return Email The current Email instance
   */
  public function sendFrom($address) {
    $address = $this->validateEmail($address) ? $address : FALSE;
    $this->from = $address;

    return $this;
  }

  /**
   * Sets the address the emailer should reply to
   *
   * This method implements a fluent interface.
   *
   * @param string $address A valid email address
   *
   * @return Email The current Email instance
   */
  public function replyTo($address) {
    $address = $this->validateEmail($address) ? $address : FALSE;
    $this->replyTo = $address;

    return $this;
  }

  /**
   * Gets the subject of the message
   *
   * @return string The subject line of the message
   */
  public function getSubject() {
    return $this->subject ?: '(No Subject)';
  }

  /**
   * Sets the subject of the message
   *
   * This method implements a fluent interface.
   *
   * @param string $subject The subject for the message
   *
   * @return Email The current Email instance
   */
  public function subject($subject) {
    $this->subject = str_replace(array("\r","\n"),array(" "," "), $subject);

    return $this;
  }

  /**
   * Gets the message body
   *
   * @return string The body of the message, wrapped at 70 characters
   */
  public function getMessage() {
    $message = $this->message;
    if(empty($message)) throw new \Exception('The body of you message is blank. The email will not be sent.');

    return wordwrap($message, 70, "\n", TRUE);
  }

  /**
   * Sets the message body
   *
   * This method implements a fluent interface.
   *
   * @param string $body The body of the message
   *
   * @return Email The current Email instance
   */
  public function message($body) {
    $this->message = filter_var(trim($body), FILTER_SANITIZE_MAGIC_QUOTES);

    return $this;
  }

  /**
   * Returns the $headers array with \r\n separating items
   *
   * @return string The concatenated string of header values
   */
  private function getHeaders() {
    if(empty($this->from)) throw new \Exception('Your email is missing the FROM address.');

    $this->headers['from'] = 'From: ' . $this->from;
    $this->headers['mailer'] = 'X-Mailer: PHP/' . phpversion();

    return implode("\r\n", $this->headers);
  }

  /**
   * Sends the email.
   */
  public function send() {
    $to      = $this->getTo();
    $subject = $this->getSubject();
    $message = $this->getMessage();
    $headers = $this->getHeaders();

    return mail($to, $subject, $message, $headers, '-f'.$this->from);
  }

  /**
   * Checks an email address against a regular expression.
   *
   * @param string $address The address to check
   *
   * @return mixed Returns validated $address, or FALSE if the filter fails
   */
  private function validateEmail($address) {
    return filter_var(trim((string) $address), FILTER_VALIDATE_EMAIL);
  }
}
