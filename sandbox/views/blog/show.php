<?php
/**
 * Blog/Show.php
 * --------------------------------------------------
 * Show the blog files from your web/content/blog folder
 */

$page->title = "News";

?>

<section id="blog">

<?php

if( !$page->error ) :
  $blogs = $page->blogs;
  $featured = array_pop( $blogs );

?>

<article class="featured">
  <div>
    <?php echo html( $featured ) ?>
    <a href="<?php echo url( $featured->url()) ?>" class="readmore">Read More&hellip;</a>
  </div>
</article>

<?php foreach( $blogs as $entry ): ?>
<article class="entry">
  <div>
    <?php echo html( $entry ) ?>
    <a href="<?php echo url( $entry->url()) ?>" class="readmore">Read More&hellip;</a>
  </div>
</article>
<?php endforeach; ?>

<?php snippet( 'pagination.php', $page ); ?>

<?php else: ?>
  <p>No posts were found</p>
<?php endif ?>

</section><!-- /#blog-list -->
