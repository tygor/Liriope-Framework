<?php

$page->title = 'Galleries and Modules';
$page->date = '2012/06/21';

?>

<h1><?= $page->title() ?></h1>
<hr class="readmore">

<h1><?= $page->title() ?></h1>
<p>I&rsquo;m building Liriope for use. So, as I use it, I have been coming across a
desired features list. Galleries and Modules are two that I have since found very
nice.</p>

<p>First was modules. I wanted to be able to call up a list of blog articles from within
a page. Sure, it is still a little buggy in that if you call the blog module to list the
blog articles from within a blog article, it creates an infinate loop. But then I thought
to use the module construct for pagination and for the navigation menu. It works
beautifully.</p>

<p>Another popular site element is the gallery. I have a site that I&rsquo;m working on
that calls for a page with a grid of pictures. That's easy enough, but how do you make a
system that is easy to update? I have no database connectivity to work with, out of
decision, so I am using a file based database: YAML. It&rsquo;s much easier to read the
syntax than XML, and easier to type due to it's leaner structure. So I grabbed the Spyc
parse for YAML and off I go!</p>

<div class="twocolumns">
  <div class="column">
    <h2>Gallery screenshot</h2>
    <img src="/content/blog/2012-06-21/gallery-example.png" style="width: 100%;">
  </div>
  <div class="column last">
    <h2>Sample of the YML code</h2>
<pre>
---
name: My Images
images:
  image-01:
    name: Image 1
    url: http://quietlunch.files.wordpress.com/2010/11/chalk-sidwalk-art-big-coke.jpg
    caption: This is an image from google search for 'chalk'
    link: /gallery/image-01
  image-02:
    name: Image 2
    url: https://encrypted-tbn0.google.com/images?q=tbn:ANd9GcQOQUOmE183IKwQwM24dMB3i8V5x7i0xdIxrcTwMCTSqI2Fjs4O
    caption: This is an image from google search for 'chalk'
    link: /gallery/image-02
</pre>
  </div>
</div>
