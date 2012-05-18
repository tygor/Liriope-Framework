<?php
// Submenu

if( !$page->isHomePage() ):

  // find the menu item that is active
  global $page;
  global $menu;
  $sub = $menu->findActive();

  // show submenu if it has children
  if( $sub && $sub->hasChildren() ) :
?>
<nav id="submenu">
  <h3><?php echo html( $sub->label ) ?></h3>
  <ul id="section-menu" class="menu">
  <?php foreach( $sub->getChildren() as $m ): ?>
    <li class="<?php echo $m->hasChildren() ? 'deeper' : '' ?><?php echo ($m->isActive()) ? ' active' : '' ?>">
      <a href="<?php echo url( $m->url ) ?>"><?php echo html( $m->label ) ?></a>
    </li>
  <?php endforeach ?>
  </ul>
</nav>
<?php
  endif;
endif;
?>
