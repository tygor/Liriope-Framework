<?php

$page->title = 'Twitter Module';
$page->date = '2012/08/18';
$page->script = ("
  $(document).ready(function() {
    $(\"#tweets\").fadeOut('slow').load('/tweets/from/nrhc/1').fadeIn('slow');
  });
");

?>

<h1><?= $page->title() ?></h1>
<p>Get tweets from a user, directly on your page.</p>
<hr class="readmore">

<h1><?= $page->title() ?></h1>
<p>A very common way of pushing your prodocut or brand is to tweet about it.
Now Liriope has a way to include these directly on your page without using a widget or plugin.</p>

<div id="tweets">
  Loading&hellip;
</div>

<p>This isn't the site I used to build this, but this site has a caching system that I need to think
about to avoid too many HTTP requests: <a href="http://www.farinspace.com/twitter-feed-website-integration/">http://www.farinspace.com/twitter-feed-website-integration/</a></p>
