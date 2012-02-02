<?php
/**
 * DefaultController.class.php
 */

class DefaultController Extends LiriopeController {
  public function show( $getVars=NULL )
  {
    // include a slider and enqueue the orbit stylesheet
# TODO: snippet function needs to not use the theme system.
    $this->_page->set( 'slider', snippet( 'default/slider' ));
    $theme = $this->_theme;
    $theme::addStylesheet( '../plugins/orbit/orbit-1.3.0.css' );
  }

  public function header( $getVars=NULL )
  {
    $this->_page->set( 'ua', LiriopeTools::getBrowser() );
  }

  public function footer( $getVars=NULL )
  {
  }

  public function navigation( $getVars=NULL )
  {
  }

  public function slider( $getVars=NULL )
  {
  }

}

