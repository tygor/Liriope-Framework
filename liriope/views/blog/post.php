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
  <?= $page->post; ?>
  <footer>
    <time>published on: <?= date( 'Y-m-d', $page->post->getPubDate()); ?></time>
  </footer>
</article><!-- #blog-post -->
