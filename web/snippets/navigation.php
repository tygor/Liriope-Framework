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
  <li><?= getLink( 'Work', '/work' . '' ); ?></li>
  <li><?= getLink( 'People', '/people' . '' ); ?></li>
  <li><?= getLink( 'About Us', '/about-us' . '' ); ?>
    <ul>
      <li><?= getLink( 'Vision', '/about-us/vision' . '' ); ?>
      <li><?= getLink( 'Glasses', '/about-us/vision/glasses' . '' ); ?>
    </ul>
  </li>
  <li class="last"><?= getLink( 'Contact Us', '/contact-us' . '' ); ?></li>
</ul>
