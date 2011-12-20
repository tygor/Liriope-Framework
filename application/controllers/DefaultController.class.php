<?php
/**
 * DefaultController.class.php
 */

class DefaultController Extends LiriopeController {
  public function show( $getVars=NULL )
  {
    $slider = new LiriopeView( 'default', 'slider' );
    $this->_template->set('slider', $slider->render(FALSE));
  }

  public function header( $getVars=NULL )
  {
  }

  public function footer( $getVars=NULL )
  {
  }
}

