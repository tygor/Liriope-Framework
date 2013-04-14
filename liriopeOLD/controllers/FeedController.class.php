<?php
//
// FeedController.class.php
//

// Direct access protection
if( !defined( 'LIRIOPE' )) die( 'Direct access is not allowed.' );

class FeedController Extends LiriopeController {

  public function show( $vars=NULL ) {
    $page = $this->getPage();

    $page->theme = 'feed';

    $item1 = new Page();
    $item1->title = 'Item 1';
    $item1->description = 'Article exceprt...';
    $item1->url = 'http://liriope.ubun/blog/1';
    $item1->time = time();

    $item2 = new Page();
    $item2->title = 'Item 2';
    $item2->description = 'Article exceprt...';
    $item2->url = 'http://liriope.ubun/blog/2';
    $item2->time = time();

    $item3 = new Page();
    $item3->title = 'Item 3';
    $item3->description = 'Article exceprt...';
    $item3->url = 'http://liriope.ubun/blog/3';
    $item3->time = time();

    $items = array( $item1, $item2, $item3 );
    
    $page->set( 'items',  $items );
  }

}

?>
