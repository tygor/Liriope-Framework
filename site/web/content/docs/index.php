<?php
// Docs homepage

$page->title = 'Docs';

?>

<h1>Documentation</h1>
<p>Time to start writing help and reference documentation so that I can remember how this machine works</p>
<h2>CMS - Content Management System</h2>
<p>By default, the content is stored under the web root, in the &ldquo;content&rdquo; folder. Pages can be created by simply putting a PHP file within the content folder.</p>
<p>At the top of each page, you can set any variables that you would like to use within the page using standard PHP variable practices, or by setting variables right to
the $page object. For example, two that I suggest always setting are $page->title and $page->date.</p>
