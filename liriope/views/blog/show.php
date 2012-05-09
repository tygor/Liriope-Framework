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

<?= getLink( 'first', '/blog/show/limit/'.$page->limitNum.'/page/1'); ?>
<?= getLink( 'prev', '/blog/show/limit/'.$page->limitNum.'/page/'.( $page->pageNum - 1 )); ?>
<?php for( $i=1; $i<=$page->totalPages; $i++ ): ?>
  <?= getLink( $i, '/blog/show/limit/'.$page->limitNum.'/page/'.$i); ?>
<?php endfor; ?>
<?= getLink( 'next', '/blog/show/limit/'.$page->limitNum.'/page/'.( $page->pageNum + 1 )); ?>
<?= getLink( 'last', '/blog/show/limit/'.$page->limitNum.'/page/'.$page->totalPages); ?>

</section><!-- /#blog-list -->
