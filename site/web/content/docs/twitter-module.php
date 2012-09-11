<?php

$page->title = 'Twitter Module';
$page->date = '2012/08/18';
$page->script = "
  $(window).load(function(){
    // Grab the last tweet
    getTweets('tyler_s_gordon', 5);
  });
";

?>

<h1><?= $page->title() ?></h1>
<p>Get tweets from a user, directly on your page.</p>
<hr class="readmore">

<h1><?= $page->title() ?></h1>

<p>A very common way of pushing your product or brand is to tweet about it.
Now Liriope has a way to include these directly on your page without using a widget or plugin.</p>

<p>Right now, the method is to create an empty &lt;div&gt; tag with an id of "tweets." Then, call the funciton
"getTweets( username, tweet_limit )" and the rest is up to Liriope. As a way of telling the user something is about
to happen, I have put the word "Loading" into the div tag which is replaced once the JS has finished.</p>

<hr>
<div id="tweets">
  Loading&hellip;
</div>
<hr>
