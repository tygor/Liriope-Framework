<?php
/**
 * Blog/Show.php
 * --------------------------------------------------
 * Show the blog files from your web/content/blog folder
 */
?>

<section id="blog">

<?php foreach( $blogs as $entry ): ?>
<article>
  <?= $entry ?>
  <footer>
    <a href="<?php echo $entry->getLink() ?>" class="readmore">Read full article&hellip;</a><br>
    <time><?php echo date( 'M jS, Y', $entry->getPubDate()) ?></time>
  </footer>
</article>
<hr>
<?php endforeach; ?>

<?php

// set the page title after adding in the blog list
// since they will set the title as well and overwrite it
View::set( 'page.title', 'Blog | Liriope' );

?>

</section><!-- /#blog-list -->
