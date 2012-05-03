<?php

class TestController extends LiriopeController {
  function show( $params ) {
    View::set( 'params', $params );
    View::set( 'content', 'Testing' );
  }
}
?>
