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
  <a href="<?php echo url( 'media' ) ?>">
    <img src="/images/slider/gospel.jpg" alt="The Gospel series" />
  </a>
  <div class="content" style="background-color: #ccc;">
    <h1>Div Tag Content!</h1>
    <p>Hi</p>
  </div>
  <img src="/images/slider/network.jpg" alt="NetWORK" data-caption="#htmlCaption" />
  <img src="/images/slider/jg-test-drive.jpg" alt="Journey Group Test Drive" />
  <img src="/images/slider/honduras.jpg" alt="Honduras" />
</div>

<!-- Captions for Orbit -->
<span class="orbit-caption" id="htmlCaption">I'm a <a href="http://en.wikipedia.org/wiki/HTML" target="_blank">HTML</a> caption</span>

<?php endif ?>
