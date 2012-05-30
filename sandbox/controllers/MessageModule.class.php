<?php
//
// MessageModule.class.php
//

// Direct access protection
if( !defined( 'LIRIOPE' )) die( 'Direct access is not allowed.' );

class MessageModule Extends LiriopeModule {

  // show()
  // displays a list of the latest blog posts
  //
  public function show( $vars=NULL ) {
    global $module;
    global $page;

    // add the Message Module CSS
    $page->css('/plugins/message-module/messages.css');

    // $vars should be the URL to an XML file
    $xml = new Xml( $vars, TRUE );
    $message = new Messages( $xml->get() );

    $module->message = $message;
  }

}

?>
