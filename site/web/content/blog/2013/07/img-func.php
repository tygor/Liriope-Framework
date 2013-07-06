<?php

$page->title = 'Image Function Test';
$page->date = '2013-07-05';

?>

<h1><?= $page->title ?></h1>
<hr class="readmore">

<h1><?= $page->title ?></h1>

<p>This post is to test the new and improved image function: img(). This function "guesses" where the image passed is in the directory structure and tries to provide the relative link for the HTML tag.</p>

<p>And the test: <img src="<?= img('clear-cache-icon.png') ?>"></p>