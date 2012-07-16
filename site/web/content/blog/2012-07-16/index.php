<?php

$page->title = "Blog depth test";
$page->date = '2012-07-16';

?>

<h1><?= $page->title() ?></h1>
<hr class="readmore">

<h1><?= $page->title() ?></h1>
<p>For some reason, when I wrote the blog controller it was only reading from the top directory of the blog. Oops.
I actually want to to read recursively into the top directory. If you're reading this page, then that is working.</p>
