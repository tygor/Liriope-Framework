<?php
/**
 * ContactusController.class.php
 */

class ContactusController Extends LiriopeController {
  public function show( $getVars )
  {
    LiriopeTools::devPrint(__METHOD__.' - This is the Contact Us controller show action');
    LiriopeTools::devPrint($getVars);
  }
}

