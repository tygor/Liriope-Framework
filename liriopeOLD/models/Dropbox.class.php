<?php

class Dropbox {
  // @var string The data returned from the Drop Box file
  private $data;

  /**
   * CONSTRUCTOR
   *
   * @param string $url The URL to the public Drop Box file
   */
  public function __construct($url=NULL) {
    if($url===NULL) throw new Exception('The Drop Box model needs a URL during construction');

    $ch = curl_init();
    curl_setopt( $ch, CURLOPT_URL, $url );
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, TRUE);
    $output = curl_exec($ch);
    curl_close($ch);
    $this->data = utf8_encode(trim($output));
  }

  /**
   * Returns the data string
   */
  public function __toString() {
    return $this->data;
  }

  /**
   * Returns the data string
   */
  public function get() {
    return $this->data;
  }
  
}
