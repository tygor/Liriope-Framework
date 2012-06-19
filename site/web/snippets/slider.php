<?php
/**
 * Slider.php
 * --------------------------------------------------
 * homepage slider
 */

// page configuration
// ------------------

$page->css( '/plugins/orbit/orbit-1.3.0.css' );
$page->js( '/plugins/orbit/jquery.orbit-1.3.0.js' );
$page->script("
jQuery(window).load( function() {
  $('#featured').orbit({
    animation: 'fade',               // fade, horizontal-slide, vertical-slide, horizontal-push
    animationSpeed: 500,             // how fast animtions are
    advanceSpeed: 5000,              // if timer is enabled, time between transitions 
    pauseOnHover: true,              // if you hover pauses the slider
    startClockOnMouseOut: true,      // if clock should start on MouseOut
    bullets: true,
  });
});
");

?>

<div id="featured"> 
  <div class="content" style="background-color: #ccc;">
    <h1>Div Tag Content!</h1>
    <p>Hi</p>
  </div>
  <img src="https://github.com/zurb/orbit/raw/master/demo/dummy-images/overflow.jpg" alt="Overflow: Hidden No More" />
  <img src="https://github.com/zurb/orbit/raw/master/demo/dummy-images/captions.jpg"  alt="HTML Captions" data-caption="#htmlCaption" />
  <img src="https://github.com/zurb/orbit/raw/master/demo/dummy-images/features.jpg" alt="and more features" />
</div>
<!-- Captions for Orbit -->
<span class="orbit-caption" id="htmlCaption">I'm a <a href="http://en.wikipedia.org/wiki/HTML" target="_blank">HTML</a> caption</span>
