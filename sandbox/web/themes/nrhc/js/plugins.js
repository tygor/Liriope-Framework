
// place any jQuery/helper plugins in here, instead of separate, slower script files.
(function($) {

    $('#slider').orbit({
      animation: 'fade',               // fade, horizontal-slide, vertical-slide, horizontal-push
      animationSpeed: 500,             // how fast animtions are
      advanceSpeed: 5000,              // if timer is enabled, time between transitions 
      pauseOnHover: true,              // if you hover pauses the slider
      startClockOnMouseOut: true,      // if clock should start on MouseOut
      directionalNav: false,           // manual advancing directional navs
      bullets: true,
    });

}(jQuery));
