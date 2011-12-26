<?php
/**
 * Footer.php
 * default footer
 */
?>
    </div>
    <footer id="main">
      <div class="fourcolumns">
        <div class="column span-2">
          <p>&copy;2012
        </div>
        <div class="column">
          <script src="http://widgets.twimg.com/j/2/widget.js"></script>
          <script>
          new TWTR.Widget({
            version: 2,
            type: 'profile',
            rpp: 4,
            interval: 30000,
            width: 200,
            height: 300,
            theme: {
              shell: {
                background: '#cccccc',
                color: '#333333'
              },
              tweets: {
                background: '#dddddd',
                color: '#000000',
                links: '#16709C'
              }
            },
            features: {
              scrollbar: false,
              loop: false,
              live: false,
              behavior: 'all'
            }
          }).render().setUser('tyler_s_gordon').start();
          </script>
        </div>
        <div class="column last">
          <?= $nav; ?>
        </div>
      </div>
    </footer>
  </div> <!--! end of #container -->


  <!-- JavaScript at the bottom for fast page loading -->

  <!-- Grab Google CDN's jQuery, with a protocol relative URL; fall back to local if offline -->
  <script src="//ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
  <script>window.jQuery || document.write('<script src="js/libs/jquery-1.6.2.min.js"><\/script>')</script>

  <!-- scripts concatenated and minified via ant build script-->
  <script defer src="js/plugins.js"></script>
  <script defer src="js/script.js"></script>
  <!-- end scripts-->
	
  <!-- Orbit slider script -->
  <script src="plugins/orbit/jquery.orbit-1.3.0.js" type="text/javascript"></script>
  <!-- end orbit -->

  <!-- Change UA-XXXXX-X to be your site's ID -->
  <script>
    window._gaq = [['_setAccount','UAXXXXXXXX1'],['_trackPageview'],['_trackPageLoadTime']];
    Modernizr.load({
      load: ('https:' == location.protocol ? '//ssl' : '//www') + '.google-analytics.com/ga.js'
    });
  </script>


  <!-- Prompt IE 6 users to install Chrome Frame. Remove this if you want to support IE 6.
       chromium.org/developers/how-tos/chrome-frame-getting-started -->
  <!--[if lt IE 7 ]>
    <script src="//ajax.googleapis.com/ajax/libs/chrome-frame/1.0.3/CFInstall.min.js"></script>
    <script>window.attachEvent('onload',function(){CFInstall.check({mode:'overlay'})})</script>
  <![endif]-->
  
</body>

