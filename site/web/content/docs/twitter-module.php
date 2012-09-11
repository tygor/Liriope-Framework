<?php

$page->title = 'Twitter Module';
$page->date = '2012/08/18';
$page->script("
  $(window).load(function(){
    // Grab the last tweet
    getTweets('tyler_s_gordon', 5);
  });
");

?>

<h1><?= $page->title() ?></h1>
<p>Get tweets from a user, directly on your page.</p>
<hr class="readmore">

<h1><?= $page->title() ?></h1>
<p>A very common way of pushing your product or brand is to tweet about it.
Now Liriope has a way to include these directly on your page without using a widget or plugin.</p>

<hr>
<div id="tweets">
  Loading&hellip;
</div>
<hr>

<ul>
  <li>Original tutorial followed from <a href="http://www.rustybrick.com/stream-twitter-on-website.html">Rusty Brick</a></li>
  <li>This tutorial has a caching system that I need to think about to avoid too many HTTP requests: <a href="http://www.farinspace.com/twitter-feed-website-integration/">Farin Space</a></li>
</ul>
