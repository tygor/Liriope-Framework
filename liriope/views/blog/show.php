<?php
/**
 * Blog/Show.php
 * --------------------------------------------------
 * Show the blog files from your web/content/blog folder
 */

$page->title = "Blog";
$page->META = '<link rel="alternate" type="application/rss+xml" title="'.$page->title.'" href="'.url('blog/feed').'" />';

?>

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
    <?php echo module( 'liriope', 'pagination', array( 'page'=>$page )) ?>
    <p style="clear: both;"><a href="<?php echo url( 'blog/feed' ) ?>"><img src="/images/rss-14x14.png" alt="Subscribe"> Subscribe</a></p>
  </footer>

</section><!-- /#blog-list -->

