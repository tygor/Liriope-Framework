<?php

class TestController extends LiriopeController {
  function show( $params ) {
    $this->set( 'params', $params );
    $this->set( 'content', 'Testing' );
  }
}
?>
