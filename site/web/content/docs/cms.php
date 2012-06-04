<?php
// Docs homepage

$page->title = 'CMS - Content Management System';
$page->date = '2012/06/03';

?>


<h1><?php echo $page->title() ?></h1>
<hr class="readmore">

<h1><?php echo $page->title() ?></h1>
<p>By default, the content is stored under the web root, in the &ldquo;content&rdquo; folder. Pages can be created by simply putting a PHP file within the content folder.</p>
<p>At the top of each page, you can set any variables that you would like to use within the page using standard PHP variable practices, or by setting variables right to
the $page object. For example, two that I suggest always setting are $page->title and $page->date.</p>
