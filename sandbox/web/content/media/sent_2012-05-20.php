<?php

$page->title = 'Sent';
$page->date = '2012-05-20';

?>
<p><img src="http://nrhc-pages.northrockhill.com/messages/2012/sent/Sent-Web-Chip.png"></p>
<hr class="readmore">

<aside>
  <?php echo module( 'message', 'show', 'http://nrhc-pages.northrockhill.com/messages/2012/sent/sent.xml' ) ?>
</aside>

<h1><?php echo $page->title ?></h1>