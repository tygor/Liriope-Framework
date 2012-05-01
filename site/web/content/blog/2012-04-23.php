<?php

self::set( 'page.title',   'Routing fun' );
self::set( 'blog.pubDate', 2012-04-23 );

?>

<h1>Routing fun</h1>
<div class="image thumb alignleft">
  <img src="/content/blog/2012-04-23/router-flowchart-thumb.png">
</div>
<p>Still learning&hellip; but things are coming together anyway.</p>

<hr class="readmore">

<pre>class: <?= get_class(); ?></pre>

<div class="image alignright">
  <img src="/content/blog/2012-04-23/router-flowchart.png">
  <p class="caption">Router flow chart&hellip; rough.<br>Chart is thanks to the
  wonderful web app <a href="http://www.diagram.ly/" target="_blank">Diagram.ly</a></p>
</div>
<h1>Routing fun</h1>
<p>Ok, last post, I discovered a fatal mistake: the router was also trying to route
HTML internal image, CSS, and JS files. Why is this a problem? Because it was attempting
to display these by taking a full trip through the MVC structure! And there was no
controller or action for them, and so they returned NULL or an ERROR or something
that I couldn't see to fix.</p>
<p>Well, not totally. If I put an image URL in the address line, it helped a bit. But
then the CSS and JS were still a problem. But Firefox turns them into links when viewing
the source, so I was able to see the errors too. In the end, here's what I learned:</p>
<ul>
<li><p><b>All image, CSS, and JS reference must be absolute from the web root</b></p>
  <p>Otherwise, for example, it was looking for the Blog page CSS, starting from
  the blog folder rather than the root.</p></li>
<li><p><b>On top of that, all images must reference the "content" folder</b></p>
  <p>Even though the file based content is in the "content" folder, this doesn't mean
  that the internal images know that. So, reference those images as if you're index.php
  file is in the web root (which it is).</p></li>
</ul>
<h2>I am not done with the router</h2>
<p>The router works as is right now, with the pages and reference that I have right now
but it is by no means finished. I plan to route by file type based on extension. I plan
to limit what file types can be used for the CMS content (which I sort of do, but it's
disjointed and the logic is in the wrong spot). And finally, I need the router to direct
to text content if a text content file is not found, and to image content if the image is
not found, etc.</p>
