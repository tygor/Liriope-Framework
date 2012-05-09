<?php
/**
 * Blog/Show.php
 * --------------------------------------------------
 * Show the blog files from your web/content/blog folder
 */

$page->title = "Blog | Liriope";
$page->css( 'content/blog/blog.css' );

?>

<section id="blog">

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

</section><!-- /#blog-list -->
