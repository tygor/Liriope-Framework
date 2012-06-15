<?php
/* --------------------------------------------------
 * Navigation snippet
 * --------------------------------------------------
 * simply the UL of links, used in the header and footer
 * within their individual NAV tags for styling
 *
 */
?>

<ul>
  <li><a href="<?php echo url('projects') ?>">Projects</a></li>
  <li class="deeper"><a href="<?php echo url('about-us') ?>">About Us</a>
    <ul>
      <li><a href="<?php echo url('about-us/vision') ?>">Vision</a>
      <li><a href="<?php echo url('about-us/vision') ?>">Vision</a>
    </ul>
  </li>
  <li><a href="<?php echo url('features') ?>">Features</a></li>
  <li><a href="<?php echo url('blog') ?>">Blog</a></li>
  <li><a href="<?php echo url('gallery') ?>">Gallery</a></li>
  <li class="last"><a href="<?php echo url('docs') ?>">Docs</a></li>
</ul>
