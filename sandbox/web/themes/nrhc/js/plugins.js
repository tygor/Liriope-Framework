
// place any jQuery/helper plugins in here, instead of separate, slower script files.
jQuery.noConflict();
jQuery(window).load(function() {
  (function($) {

    $('#slider').orbit({
      animation: 'fade',               // fade, horizontal-slide, vertical-slide, horizontal-push
      animationSpeed: 500,             // how fast animtions are
      advanceSpeed: 5000,              // if timer is enabled, time between transitions 
      pauseOnHover: true,              // if you hover pauses the slider
      startClockOnMouseOut: true,      // if clock should start on MouseOut
      bullets: true,
    });

  })(jQuery);
});
