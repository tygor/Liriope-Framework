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
  <?= $post; ?>
  <footer>
    <time>published on: <?= date( 'Y-m-d', $post->getPubDate()); ?></time>
  </footer>
</article><!-- #blog-post -->
