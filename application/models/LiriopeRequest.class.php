<?php
/**
 * LiriopeRequest.class.php
 */

class LiriopeRequest
{
  public function __construct()
  {
  }

  /**
   * findController
   *
   * @since 1.0
   */
  public function findController()
  {
    $uri = $_SERVER['REQUEST_URI'];
    return $uri;
  }
}
?>
