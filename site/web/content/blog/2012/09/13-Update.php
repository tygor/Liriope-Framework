<?php

$page->title = "Sep update";
$page->date = '2012-09-13';

?>

<h1><?= $page->title ?></h1>
<hr class="readmore">

<h1><?= $page->title ?></h1>
<p>I've been using the Liriope Framework on a few sites now with total success. As I go, of course, I find issues that I post to GitHub and then
when I'm home, I smooth them out. Or I try. So, as it is, I have one issue outstanding, but I think that it's a problem with the specific
site controls rather than Liriope as a whole. So, BETA is going well.</p>

<p>Murphy's Law strikes. As I am testing the very post, I am noticing that the older blog posts are all declaring the same date up to a point, then
the continue as normal. So, what did I change to cause this?</p>

<h2>UPDATE:</h2>

<p>Weird. For some reason, a handful of older blog posts used $blog rather than $page to set the date. This was causing the problem, resulting in a sorting
issue. The date field is defaulted to the file modify time, so with a pull request or checkout or something, the files were updated to a more
visible date/time (they were buried before). Fixed now.</p>
