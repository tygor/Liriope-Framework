<?php

$page->title = "Up next... caching";
$page->date = '2012-05-13 21:58:34';

?>

<h1><?php echo $page->title ?></h1>
<hr class="readmore">

<h1><?php echo $page->title ?></h1>
<p>My goal for what's next is caching. I can already tell that grabbing all blog pages to read the internal
pubdate will take extra processing power. And that caching is a way to speed up following pages.</p>
<p>I did a <a href="http://www.google.com/search?q=page+caching+with+php" target="_blank">quick web search</a>
and read the first article, and it seems simple enough. I'll have to beef
up my file class to enable read/write, and also write in a means to clear the cache. A _GET parameter,
or maybe a URI link.</p>
<p>I'll keep looking for ways to make it better.</p>
