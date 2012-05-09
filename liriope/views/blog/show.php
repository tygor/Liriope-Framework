<?php
/**
 * Blog/Show.php
 * --------------------------------------------------
 * Show the blog files from your web/content/blog folder
 */

$page->title = "Blog | Liriope";

?>

<section id="blog">

<?php foreach( $page->blogs as $entry ): ?>
<article>
  <?= $entry ?>
  <footer>
    <a href="<?php echo $entry->getLink() ?>" class="readmore">Read full article&hellip;</a><br>
    <time><?php echo date( 'M jS, Y', $entry->getPubDate()) ?></time>
  </footer>
</article>
<hr>
<?php endforeach; ?>

</section><!-- /#blog-list -->
