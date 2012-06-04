<?php
/**
 * Blog/Show_docs.php
 * --------------------------------------------------
 * Show the blog files from your web/content/docs folder
 */

$page->title = "Docs | Liriope";

?>

<h1>Documentation</h1>
<p>Time to start writing help and reference documentation so that I can remember how this machine
works.</p>

<section id="blog">

<?php if( $page->error ) : ?>
  <h1>There were no blog posts </h1>
<?php else: ?>
<?php foreach( $page->blogs as $entry ): ?>
<article>
  <footer>
    <p>
      <time class="month"><?php echo date( 'M', $entry->time()) ?></time><br>
      <time class="day"><?php echo date( 'd', $entry->time()) ?></time>
    </p>
  </footer>
  <div>
    <?php echo $entry->intro() ?>
  </div>
</article>
<?php endforeach; ?>
<?php endif ?>

  <footer>
    <?php snippet( 'pagination.php', $page ); ?>
  </footer>

</section><!-- /#blog-list -->

