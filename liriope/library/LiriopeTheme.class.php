<?php
/**
 * LiriopeThemeController.class.php
 * 
 * A controller specifically for the chrome or theme or template
 * of the site.
 * 
 * expected functions:
 * headerSnIppet, footerSnippet, navSnippet
 */

class LiriopeThemeController Extends LiriopeController {

  public function showTheme( $getVars=NULL )
  {
  }

  public function headerSnippet( $getVars=NULL )
  {
    $this->set( 'ua', LiriopeTools::getBrowser() );
  }

  public function footerSnippet( $getVars=NULL )
  {
  }

  public function navigationSnippet( $getVars=NULL )
  {
  }

  public function sliderSnippet( $getVars=NULL )
  {
  }

}

