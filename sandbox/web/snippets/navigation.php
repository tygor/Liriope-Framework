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
  <li><?= getLink( 'Projects', '/projects' . '' ); ?></li>
  <li class="deeper"><?= getLink( 'About Us', '/about-us' . '' ); ?>
    <ul>
      <li><?= getlink( 'vision', '/about-us/vision' . '' ); ?>
    </ul>
  </li>
  <li class="last"><?= getLink( 'Features', '/features' . '' ); ?></li>
</ul>
