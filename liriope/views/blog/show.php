<?php
/**
 * Blog/Show.php
 * --------------------------------------------------
 * Show the blog files from your web/content/blog folder
 */
?>

<section id="blog">

<?php foreach( page::get( 'blogs' ) as $entry ): ?>
<article>
  <?= $entry->getIntro(); ?>
  <footer>
    <a href="<?= $entry->getLink(); ?>" class="readmore">Read full article&hellip;</a><br>
    <time><?= $entry->getModified(); ?></time>  <?= date( 'Y-m-d H:i:s', $entry->getPubDate()); ?>
  </footer>
</article>
<hr>
<?php endforeach; ?>

<?php

// set the page title after adding in the blog list
// since they will set the title as well and overwrite it
page::set( 'page.title', 'Blog' );

?>

</section><!-- /#blog-list -->
