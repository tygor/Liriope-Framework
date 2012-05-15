<?php

$blog->title = "Progress is rewarding";
$blog->date = "2012-05-09";

?>

<h1><?= $blog->title; ?></h1>
<hr class='readmore'>

<h1><?= $blog->title; ?></h1>

<p>It&rsquo;s nice to see forward movement. Not to say that I won&rsquo;t backslide anytime
soon. It seems to go that way with coding. You move quickly to a point, discover flaws,
then move back to the start and hone and organize. It&rsquo;s good though.</p>

<p>So, where are we now? I have a working CMS that is file based. It is (so far) working
properly and in-page variables can be set. Stylesheets and javascript can also be queued
in-page. So, for the most part, it&rsquo;s looking good!</p>

<p>We also have hijacked the blog folder within the CMS and turned it&rsquo;s contents
into a blog. Some work is still needed. Here is a short list: </p>

<ul>
  <li>Pagination</li>
  <li>Better styling</li>
  <li>Ability to set page title from the blog (which is working for the page object, but not the blog object.)</li>
</ul>

