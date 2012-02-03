<?php
/**
 * DefaultController.class.php
 */

class DefaultController Extends LiriopeController {
  public function show( $getVars=NULL )
  {
    // include a slider and enqueue the orbit stylesheet
    $theme = $this->_theme;
    $theme::addStylesheet( '../plugins/orbit/orbit-1.3.0.css' );
  }

}

?>

