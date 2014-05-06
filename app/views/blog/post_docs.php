<?php
/**
 * Blog/Post_docs.php
 * --------------------------------------------------
 * Show a single post
 */

$this->setTheme('paper');
$this->title = $page->post->title();

?>

<article id="blog-post" class="docs">
  <header>
  </header>
  <?php echo $page->post->article(); ?>
  <footer>
    <time>published on: <?php echo date( 'Y-m-d', $page->post->time()); ?></time>
  </footer>
</article><!-- #blog-post -->
