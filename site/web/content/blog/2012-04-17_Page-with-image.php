<?php

self::set( 'page.title', 'A page with an image' );
self::set( 'blog.pubDate', '2012-04-17' );
self::addStylesheet( 'content/blog/2012-04-17/styles.css' );

?>

<h1>A page with an image</h1>
<p>Now that we have a content router, how do we link to images?</p>
<hr class="readmore">

<h1>A page with an image</h1>
<img src="/content/blog/2012-04-17/placeholder.gif" alt="image from blog root">
<p>Now that we have a content router, how do we link to images?</p>
<img src="/media/grass.png" alt="image from webroot/media folder">
<p>The router controlls all requests to the site starting from the web root. So an image
request will be thrown throgh the routing rules. This means that the rules need to be
able to test the extension and if it is not an approved "content" extension (PHP, HTML, 
HTM, TXT&mdash;and truly, just PHP if you think about it) then it returns the raw file.</p>
<p>I think that <a href="https://github.com/bastianallgeier/kirbycms" target="_blank">KirbyCMS</a>
has this ability (it would have to), and I think I've seen the class that manages this.
I'll have to take a look</p>
<hr>
<h2>Update after looking at Kirby</h2>
<p>I love to hate brilliant people! So, there is no easy answer to this problem by simply
looking at someone elses code. Especially when it is highly file-based and spread out. So,
other than not finding what I wanted, I was highly inspired and motivated to utilize some
knowledge from simply looking at Kirby's code. Wait&hellip; yep&hellip; here it comes&mdash;I'm
tired now, overwhelmed by the awesome task it would be to make this project as nice
as Kirby.</p>
<p>But I am commited to do this myself since it is in fact a learning project!</p>
<p>And for now, the image that exists is this blog post still is not showing above. *Sigh*</p>
