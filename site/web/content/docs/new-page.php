<?php

$page->title = 'Creating a new page';
$page->date = 'July 19, 2012';
$page->keywords = 'new, page, create, write, content';

?>

<h1><?= $page->title() ?></h1>
<p>Walking through creating a new page inside of Liriope.</p>
<hr class="readmore">

<h1><?= $page->title() ?></h1>
<p>Creating a page in Liriope can be fairly easy. First, you'll need FTP access to the site, or some other
means of adding/editing a file on your web host. After that, it's merely writing to a file within your site
structure.</p>

<p>By default, Liriope content is placed withing the web root inside the 'content' folder. This gets the content
to a place that Liriope can use, but for regular pages, an additional step is required. If, however, you are adding
to a feed folder like your blog, then the act of writing the file creates the new page.</p>

<h2>Let's start</h2>

<p>Create a new file in the 'content' folder for your Liriope site. Each page can take any settings in the form
of variables. These can then be used by your controllers and view files. The basic settings that should be set
on most if not all pages is the title and date. Here's how:</p>

<script src="https://gist.github.com/3146417.js?file=lB-page-settings.php"></script>

<p>The title is added to the <code>&lt;title&gt;</code> tag by default and that can be changed in your theme file.
The date field is used as a publish date for blogs, sorting, and as a way to remind yourself of when the page was
last updated. You could add an additional field <code>$page-&gt;creation_date = 'date string';</code> if you like.
You can organize your code as you like.</p>

<h2>Readmore</h2>

<p>One system I've used in many different CMS backends is the 'read more' break. I've seen it many different ways
but for Liriope, I use a special class name. This can be defined in the configuration settings per site
<code>c::set('readmore.class', 'abstract');</code> and can be whatever you like. Then Liriope display
up to this point for the excerpt's of blogs and RSS feeds, and omit the preceeding for the full page article (which
can also be set to preserve or omit <code>c::set('blog.intro.show', FALSE);</code>).
