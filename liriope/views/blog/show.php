<?php
/**
 * Blog/Show.php
 * --------------------------------------------------
 * Show the blog files from your web/content/blog folder
 */
?>

<?php foreach( page::get( 'blogs' ) as $entry ): ?>
<article>
  <?= $entry; ?>
  <footer>
    <a href="<?= $entry->getLink(); ?>" class="readmore">Read full article&hellip;</a><br>
    <time><?= $entry->getModified(); ?>
  </footer>
</article>
<hr>
<?php endforeach; ?>
