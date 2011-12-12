<?php
/**
 * DefaultController.class.php
 */

class DefaultController Extends LiriopeController {
  public function show()
  {
    $header = new LiriopeView( 'default', 'header' );
    $footer = new LiriopeView( 'default', 'footer' );
    $this->_template->set('header', $header->render(FALSE));
    $this->_template->set('footer', $footer->render(FALSE));
  }
}

