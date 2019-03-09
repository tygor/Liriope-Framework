<?php
// Menu
$page = $module->page();
$menu = $module->menu();
$menuclass = $module->menuclass();
?>

<?php if( !$module->error ): ?>

<ul class="menu top <?= $menuclass ?>">
  <?php foreach( $menu->getChildren() as $child ): ?>
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
