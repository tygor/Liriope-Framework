<?php
/**
 * Slider.php
 * --------------------------------------------------
 * homepage slider
 */

if( $page->isHomePage() ):

// page configuration
// ------------------

// Load these in the theme config.
// If they are left here and called after adding the CSS to 
// the theme head, then they will be omitted.
//$page->css( 'plugins/orbit/orbit-1.3.0.css' );
//$page->js( 'plugins/orbit/jquery.orbit-1.3.0.js' );

?>

<div id="slider"> 
  <?php snippet( 'slider/discover.php' ) ?>
  <?php snippet( 'slider/man-card.php' ) ?>
  <?php snippet( 'slider/automate-important.php' ) ?>
</div>

<!-- Captions for Orbit -->
<span class="orbit-caption" id="htmlCaption">I'm a <a href="http://en.wikipedia.org/wiki/HTML" target="_blank">HTML</a> caption</span>

<?php endif ?>
