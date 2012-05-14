<?php

// Direct access protection
if( !defined( 'LIRIOPE' )) die( 'Direct access is not allowed.' );

//
// Site.class.php
//

class Site extends obj {
  
  // construct()
  // Builds the site object
  // This object should hold everything site related
  function __construct() {
    // check that a site url is stored
    if( !c::get( 'url' )) c::set( 'url', server::get( 'http_host' ));
  }

}

?>