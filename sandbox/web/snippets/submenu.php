<?php
// Submenu

if( !$page->isHomePage() ):

  // find the menu item that is active
  global $page;
  global $menu;
  $sub = $menu->findDeep( $page->uri());
  $parent = $sub->getRoot();

  if( is_object( $parent )) :
?>
<nav id="submenu">
  <h3><?php echo $parent->label ?></h3>
  <ul id="section-menu" class="menu">
    <li>
      <a href="<?php echo url() ?>">Home</a>
    </li>
  </ul>
</nav>
<?php
  endif;
endif;
?>
