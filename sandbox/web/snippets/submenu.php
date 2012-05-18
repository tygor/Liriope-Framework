<?php
// Submenu

if( !$page->isHomePage() ):

  // find the menu item that is active
  global $page;
  global $menu;
  $sub = $menu->findActive();

  if( $sub ) :
?>
<nav id="submenu">
  <h3><?php echo $sub->label ?></h3>
  <ul id="section-menu" class="menu">
  <?php foreach( $sub->getChildren() as $m ): ?>
    <li<?php echo $m->hasChildren() ? ' class="deeper"' : '' ?>>
      <a href="<?php echo url( $m->url ) ?>" <?php echo ($page->isActive( $m->url )) ? ' class="active"' : '' ?>><?php echo $m->label ?></a>
    </li>
  <?php endforeach ?>
  </ul>
</nav>
<?php
  endif;
endif;
?>
