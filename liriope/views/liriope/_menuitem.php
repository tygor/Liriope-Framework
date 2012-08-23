<li<?= $hasChildren ? ' class="deeper"' : '' ?>>
  <a href="<?= url( $url ) ?>" <?php echo ($isActive) ? ' class="active"' : '' ?>><?= html($label) ?></a>
  <?php if( $hasChildren ): ?>
  <ul class="children">
    <?php foreach( $children as $child ): ?>
    <?php partial( 'liriope', 'menuitem', array(
      'hasChildren' => $child->hasChildren(),
      'children'    => $child->getChildren(),
      'url'         => $child->url,
      'label'       => $child->label,
      'isActive'    => $child->isActive
    )) ?>
    <?php endforeach ?>
   </ul>
   <?php endif ?>
</li>
