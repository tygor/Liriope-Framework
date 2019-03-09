<?php

if(isset($hasActiveChild) && $hasActiveChild) $cssclass = 'active--parent';
elseif($isActive) $cssclass = 'active';
else $cssclass = '';

?>

<li<?= $hasChildren ? ' class="deeper"' : '' ?>>
  <a href="<?= url( $url ) ?>" class="<?php echo $cssclass ?>"><?= html($label) ?></a>
  <?php if( $hasChildren ): ?>
  <ul class="menu children">
    <?php foreach( $children as $child ): ?>
    <?php partial( 'liriope', 'menuitem', array(
      'hasChildren' => $child->hasChildren(),
      'hasActiveChild' => $child->hasActiveChild(),
      'children'    => $child->getChildren(),
      'url'         => $child->getURL(),
      'label'       => $child->getLabel(),
      'isActive'    => $child->isActive()
    )) ?>
    <?php endforeach ?>
   </ul>
   <?php endif ?>
</li>
