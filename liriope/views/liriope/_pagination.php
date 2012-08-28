<?php
// Pagination
// show the below if the total pages is > 1
$page = $module->page();
$url = $page->paginationURL;
if( $page->totalPages > 1 ):
?>

<ul class='pagination'>
  <li class="arrow<?php if( $page->pageNum <= 1 ) echo ' unavailable' ?>">
    <a href="<?php if( $page->pageNum > 1 ) { echo url($url . ($page->pageNum-1)); } else { echo '#'; } ?>" class="prev">&laquo;</a>
  </li>
  <?php for( $i=1; $i<=$page->totalPages; $i++ ): ?>
    <?php $class = ( $i == $page->pageNum ) ? 'current' : ''; ?>
    <li>
      <a href="<?= url( $url . $i) ?>" class="<?php echo $class ?>"><?php echo $i ?></a>
    </li>
  <?php endfor; ?>
  <li class="arrow<?php if( $page->pageNum >= $page->totalPages ) echo ' unavailable' ?>">
    <a href="<?php if( $page->pageNum < $page->totalPages ) { echo  url( $url . ($page->pageNum+1)); } else { echo '#'; } ?>" class="last">&raquo;</a>
  </li>
</ul>

<?php endif; ?>

