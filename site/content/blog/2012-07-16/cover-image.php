<?php

$page->title = 'Cover image test';
$page->date = '2012-07-16';
$page->cover = '/content/blog/2012-07-16/cover.jpg';
$page->cover_thumb = '/content/blog/2012-07-16/cover.jpg';

?>

<h1><?= $page->title() ?></h1>
<hr class="readmore">

<img src="<?= $page->cover() ?>">
<h1><?= $page->title() ?></h1>
