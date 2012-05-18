<?php
/**
 * Blog/Post.php
 * --------------------------------------------------
 * Show a single post
 */
?>

<article id="blog-post">
  <div>
    <?php echo html( $page->post ) ?>
  </div>
  <footer>
    <time>published on: <?php echo date( 'Y-m-d', $page->post->getPubDate()); ?></time>
  </footer>
</article><!-- #blog-post -->
