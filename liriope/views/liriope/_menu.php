<?php
// Menu
$page = $module->page();
$menu = $module->menu();
?>

<?php if( !$module->error ): ?>

<ul>
  <?php foreach( $menu->getChildren() as $m ): ?>
  <li<?php echo $m->hasChildren() ? ' class="deeper"' : '' ?>>
    <a href="<?php echo url( $m->url ) ?>" <?php echo ($m->isActive()) ? ' class="active"' : '' ?>><?php echo html( $m->label ) ?></a>
    <?php if( $m->hasChildren() ): ?>
    <ul>
      <?php foreach( $m->getChildren() as $n ): ?>
      <li<?php echo $n->hasChildren() ? ' class="deeper"' : '' ?>>
        <a href="<?php echo url( $n->url ) ?>" <?php echo ($n->isActive()) ? ' class="active"' : '' ?>><?php echo html($n->label) ?></a>
        <?php if( $n->hasChildren() ): ?>
        <ul>
          <?php foreach( $n->getChildren() as $o ): ?>
          <li<?php echo $o->hasChildren() ? ' class="deeper"' : '' ?>>
            <a href="<?php echo url( $o->url ) ?>" <?php echo ($o->isActive()) ? ' class="active"' : '' ?>><?php echo html( $o->label ) ?></a>
          </li>
          <?php endforeach ?>
         </ul>
         <?php endif ?>
      </li>
      <?php endforeach ?>
     </ul>
     <?php endif ?>
  </li>
  <?php endforeach ?>
</ul>

<?php else: ?>
<h1>Woops!</h1>
<?php endif ?>
