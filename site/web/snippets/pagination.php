<?php
// Pagination
// show the below if the total pages is > 1
if( $page->totalPages > 1 ):
?>

<div class='pagination'>
<?= getLink( 'first', '/blog/show/limit/'.$page->limitNum.'/page/1', array( 'class'=> 'first' )); ?>
<?php if( $page->pageNum > 1 ) : ?>
  <?= getLink( 'prev', '/blog/show/limit/'.$page->limitNum.'/page/'.( $page->pageNum - 1 ), array( 'class'=> 'prev' )); ?>
<?php else: ?>
  <span>prev</span>
<?php endif; ?>
<?php for( $i=1; $i<=$page->totalPages; $i++ ): ?>
  <?php $current = ( $i == $page->pageNum ) ? 'page current' : 'page'; ?>
  <?= getLink( $i, '/blog/show/limit/'.$page->limitNum.'/page/'.$i, array( 'class'=> $current )); ?>
<?php endfor; ?>
<?php if( $page->pageNum < $page->totalPages ) : ?>
  <?= getLink( 'next', '/blog/show/limit/'.$page->limitNum.'/page/'.( $page->pageNum + 1 ), array( 'class'=> 'next' )); ?>
<?php else: ?>
  <span>next</span>
<?php endif; ?>
<?= getLink( 'last', '/blog/show/limit/'.$page->limitNum.'/page/'.$page->totalPages, array( 'class'=> 'last' )); ?>
</div>

<?php endif; ?>
