<?php
/* --------------------------------------------------
 * Navigation snippet
 * --------------------------------------------------
 * simply the UL of links, used in the header and footer
 * within their individual NAV tags for styling
 *
 */
?>

<ul id="global-menu" class="menu">
  <li<?php echo ($page->isActive( 'home' )) ? ' class="active"' : '' ?>>
    <a href="<?php echo url() ?>" class="home">
      <img src="<?php echo theme::$folder . '/images/home.png' ?>" alt="Home">
      <span class="image-title">Home</span>
    </a>
  </li>
  <li<?php echo ($page->isActive( 'projects' )) ? ' class="active"' : '' ?>>
    <a href="<?php echo url('projects') ?>">Projects</a>
  </li>
  <li class="<?php echo ($page->isActive( 'about-us' )) ? 'active' : '' ?> deeper">
    <a href="<?php echo url('about-us') ?>">About Us</a>
    <ul>
        <li<?php echo ($page->isActive( 'about-us/vision' )) ? ' class="active"' : '' ?>>
        <a href="<?php echo url('about-us/vision') ?>">Vision</a>
    </ul>
  </li>
  <li<?php echo ($page->isActive( 'features' )) ? ' class="active"' : '' ?>>
    <a href="<?php echo url('features') ?>">Features</a></li>
  <li<?php echo ($page->isActive( 'blog' )) ? ' class="active"' : '' ?>>
    <a href="<?php echo url('blog') ?>">Blog</a></li>
  <li class="<?php echo ($page->isActive( 'docs' )) ? ' class="active"' : '' ?> last">
    <a href="<?php echo url('docs') ?>">Docs</a></li>
</ul>
