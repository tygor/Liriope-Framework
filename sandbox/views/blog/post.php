<?php
/**
 * Blog/Post.php
 * --------------------------------------------------
 * Show a single post
 */
?>

<article id="blog-post">
  <div>
    <?php echo html( $page->post->article() ) ?>
  </div>
  <footer>
    <time>published on: <?php echo date( 'Y-m-d', $page->post->time()); ?></time>
  </footer>
</article><!-- #blog-post -->
