<?php

use Liriope\Component\Counter\Counter;

$page->title = 'Gallery';

?>

<h1>Gallery</h1>

<div class="gallery">
  <?php Counter::init(4) ?>
  <?php foreach( $page->images() as $image ) : ?>
  <?php if( Counter::first() ): $class=Counter::last() ? ' last':'' ?><div class="fourcolumns"><?php endif ?>
    <div class="column<?= $class ?>">
      <div class="frame">
        <?php if( isset( $image->link )): ?><a href="<?php echo url( $image->link ) ?>"><?php endif ?>
        <span class="image" style="background-image: url('<?= $image->url ?>');"></span>
        <?php if( isset( $image->link )): ?></a><?php endif ?>
      </div>
      <?php if( isset( $image->caption )): ?><p><?php echo $image->caption ?></p><?php endif ?>
    </div>
  <?php if( Counter::last() ): ?></div><?php endif ?>
  <?php Counter::add() ?>
  <?php endforeach ?>
</div>
