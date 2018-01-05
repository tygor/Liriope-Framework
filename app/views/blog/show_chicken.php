<?php
/**
 * Blog/Show.php
 * --------------------------------------------------
 * Show the blog files from your web/content/blog folder
 */

$this->title = "Chicken News";
$this->META = '<link rel="alternate" type="application/rss+xml" title="'.$page->title.'" href="'.url('blog/feed').'" />';

?>

<h2>Did someone say chicken?</h2>
<ul class="menu">
<?php foreach( $this->blogs as $blog ): ?>
  <li style="clear: both;">
    <img src="<?php echo $blog->cover() ?>" align="left">
    <time><?php echo date( 'M, j', $blog->time()) ?></time>
    <a href="<?= url($blog->url()) ?>"><?php echo $blog->title() ?></a>
  </li>
<?php endforeach ?>
</ul>

