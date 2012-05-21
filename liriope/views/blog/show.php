<?php
/**
 * Blog/Show.php
 * --------------------------------------------------
 * Show the blog files from your web/content/blog folder
 */

$page->title = "Blog | Liriope";

?>

<section id="blog">

<?php if( $page->error ) : ?>
  <h1>There were no blog posts </h1>
<?php else: ?>
<?php foreach( $page->blogs as $entry ): ?>
<article>
  <footer>
    <p>
      <time class="month"><?php echo date( 'M', $entry->getPubDate()) ?></time><br>
      <time class="day"><?php echo date( 'd', $entry->getPubDate()) ?></time>
    </p>
  </footer>
  <div>
    <?= $entry ?>
  </div>
</article>
<?php endforeach; ?>
<?php endif ?>

<?php snippet( 'pagination.php', $page ); ?>

</section><!-- /#blog-list -->
