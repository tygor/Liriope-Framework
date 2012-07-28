<?php
// Menu
$page = $module->page();
$menu = $module->menu();
?>

<?php if( !$module->error ): ?>

<ul class="top">
  <?php foreach( $menu->getChildren() as $child ): ?>
  <?php partial( 'liriope', 'menuitem', array(
    'hasChildren' => $child->hasChildren(),
    'children'    => $child->getChildren(),
    'url'         => $child->url,
    'label'       => $child->label,
    'isActive'    => $child->isActive
  )) ?>
  <?php endforeach ?>
</ul>

<?php else: ?>
<h1>Woops!</h1>
<?php endif ?>
