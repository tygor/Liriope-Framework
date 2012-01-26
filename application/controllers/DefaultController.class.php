<?php
/**
 * DefaultController.class.php
 */

class DefaultController Extends LiriopeController {
  public function show( $getVars=NULL )
  {
  }

  public function header( $getVars=NULL )
  {
    $this->_template->set( 'ua', LiriopeTools::getBrowser() );
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

