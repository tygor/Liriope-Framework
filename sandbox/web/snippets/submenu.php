<?php
// Submenu
?>

<?php if( !$page->isHomePage() ): ?>
<nav id="submenu">
  <h3><?php echo $page->title() ?></h3>
  <ul id="section-menu" class="menu">
    <li>
      <a href="<?php echo url() ?>">Home</a>
    </li>
  </ul>
</nav>
<?php endif ?>
