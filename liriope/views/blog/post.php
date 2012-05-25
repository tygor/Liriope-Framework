<?php
/**
 * Blog/Post.php
 * --------------------------------------------------
 * Show a single post
 */
?>

<article id="blog-post">
  <header>
  </header>
  <?php echo $page->post->article(); ?>
  <footer>
    <time>published on: <?php echo date( 'Y-m-d', $page->post->time()); ?></time>
  </footer>
</article><!-- #blog-post -->
