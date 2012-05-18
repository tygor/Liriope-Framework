<?php
/**
 * Blog/Show.php
 * --------------------------------------------------
 * Show the blog files from your web/content/blog folder
 */

$page->title = "News";

$blogs = $page->blogs;
$featured = array_pop( $blogs );

?>

<section id="blog">

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

</section><!-- /#blog-list -->
