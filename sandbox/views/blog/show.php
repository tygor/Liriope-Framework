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
  if( $page->pageNum == 1) {
    $featured = array_pop( $blogs );
  }

?>

<?php if( $featured ): ?>
  <article class="featured">
    <div>
      <?php echo html( $featured->intro() ) ?>
      <a href="<?php echo url( $featured->url()) ?>" class="readmore">Read More&hellip;</a>
    </div>
  </article>
<?php endif ?>

<?php counter::init( 2 ) ?>
<?php foreach( $blogs as $entry ): ?>
  <article class="entry">
    <div>
      <?php echo html( $entry->intro() ) ?>
      <a href="<?php echo url( $entry->url()) ?>" class="readmore">Read More&hellip;</a>
    </div>
  </article>
<?php counter::add() ?>
  <?php if( counter::last() ): ?><hr class='clear'><?php endif ?>
<?php endforeach; ?>
  <?php if( !counter::last()): ?><hr class='clear'><?php endif ?>

  <footer>
    <a href="<?php echo url( '/news/feed' ) ?>" class="rss">Subscribe</a>
    <?php echo module( 'liriope', 'pagination', $page ) ?>
  </footer>

<?php else: ?>
  <p>No posts were found</p>
<?php endif ?>

</section><!-- /#blog-list -->


