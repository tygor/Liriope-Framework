<h1>Gallery</h1>

<div class="gallery">
  <?php counter::init(4) ?>
  <?php foreach( $page->images() as $image ) : ?>
  <?php if( counter::first() ): $class=counter::last() ? ' last':'' ?><div class="fourcolumns"><?php endif ?>
    <div class="column<?= $class ?>">
      <?php if( isset( $image['link'] )): ?><a href="<?php echo url( $image['link'] ) ?>"><?php endif ?>
      <img src="<?= $image['url'] ?>">
      <?php if( isset( $image['link'] )): ?></a><?php endif ?>
      <?php if( isset( $image['caption'] )) echo $image['caption'] ?>
    </div>
  <?php if( counter::last() ): ?></div><?php endif ?>
  <?php counter::add() ?>
  <?php endforeach ?>
</div>
