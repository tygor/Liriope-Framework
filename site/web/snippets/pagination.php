<?php
// Pagination
// show the below if the total pages is > 1
if( $page->totalPages > 1 ):
?>

<div class='pagination'>
  <a href="<?php echo url('blog/show/limit/'.$page->limitNum.'/page/1') ?>" class="first">&laquo;</a>
  <?php if( $page->pageNum > 1 ) : ?>
    <a href="<?php echo url(
      '/blog/show' .
      '/page/' . ( $page->pageNum - 1 ) .
      '/limit/' . $page->limitNum
      ) ?>" class="prev">&lt;</a>
  <?php else: ?>
    <span>&lt;</span>
  <?php endif; ?>
  <?php for( $i=1; $i<=$page->totalPages; $i++ ): ?>
    <?php $class = ( $i == $page->pageNum ) ? 'page current' : 'page'; ?>
    <a href="<?php echo url(
      '/blog/show' .
      '/page/'.$i .
      '/limit/'.$page->limitNum
      ) ?>" class="<?php echo $class ?>"><?php echo $i ?></a>
  <?php endfor; ?>
  <?php if( $page->pageNum < $page->totalPages ) : ?>
    <a href="<?php echo url(
      '/blog/show' .
      '/page/'.( $page->pageNum + 1 ) .
      '/limit/'.$page->limitNum
      ) ?>" class="next">&gt;</a>
  <?php else: ?>
    <span>&gt;</span>
  <?php endif; ?>
  <a href="<?php echo url(
    '/blog/show' .
    '/page/'.$page->totalPages .
    '/limit/'.$page->limitNum
    ) ?>" class="last">&raquo;</a>
</div>

<?php endif; ?>
