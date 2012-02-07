<?php
/**
 * DefaultController.class.php
 */

class DefaultController Extends LiriopeController {
  public function show( $getVars=NULL )
  {
    theme::addStylesheet( '../plugins/orbit/orbit-1.3.0.css' );
  }

}

?>

