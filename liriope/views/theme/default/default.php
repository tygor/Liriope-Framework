<?php
/**
 * default.php
 * Default theme
 */
  echo $this->get( 'page.DOCTYPE' );
?>
<!-- paulirish.com/2008/conditional-stylesheets-vs-css-hacks-answer-neither/ -->
<!--[if lt IE 7]> <html class="no-js ie6 oldie" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js ie7 oldie" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js ie8 oldie" lang="en"> <![endif]-->
<!-- Consider adding an manifest.appcache: h5bp.com/d/Offline -->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head>
  <meta charset="utf-8">

  <!-- Use the .htaccess and remove these lines to avoid edge case issues.
       More info: h5bp.com/b/378 -->
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

  <title><?= $this->get( 'page.title' ); ?></title>
  <meta name="description" content="">
  <meta name="author" content="">

  <!-- Mobile viewport optimized: j.mp/bplateviewport -->
  <meta name="viewport" content="width=device-width,initial-scale=1">

  <!-- Place favicon.ico and apple-touch-icon.png in the root directory: mathiasbynens.be/notes/touch-icons -->

  <!-- CSS: implied media=all -->
  <!-- CSS concatenated and minified via ant build script-->
  <?= $this->getStylesheets(); ?>
  <script src="/js/libs/less-1.1.5.min.js" type="text/javascript"></script>
  <script type="text/javascript" charset="UTF-8">
    less.watch();
  </script>

  <link rel="stylesheet" href="plugins/orbit/orbit-1.3.0.css">
  <!-- end CSS-->

  <!-- FONTS -->
  <link href='http://fonts.googleapis.com/css?family=PT+Sans|Podkova' rel='stylesheet' type='text/css'>
  <!-- end FONTS -->

  <!-- More ideas for your <head> here: h5bp.com/d/head-Tips -->

  <!-- All JavaScript at the bottom, except for Modernizr / Respond.
       Modernizr enables HTML5 elements & feature detects; Respond is a polyfill for min/max-width CSS3 Media Queries
       For optimal performance, use a custom Modernizr build: www.modernizr.com/download/ -->
  <script src="/js/libs/modernizr-2.0.6.min.js"></script>
</head>

<body class="<?= "$ua[shortname] $ua[shortname]$ua[shortver] $ua[platform]"; ?>">

  <div id="container">
    <header id="main" class="clear">

      <hgroup id="identity">
        <a href="/">
          <img src="" alt="Logo" height="48" width="199" style="background-color: gray; height: 48px; width: 199px; display: block;">
        </a>
      </hgroup>

      <nav id="main" class="menu">
        <ul>
          <li><?= getLink( 'Work', '/work' . '' ); ?></li>
          <li><?= getLink( 'People', '/people' . '' ); ?></li>
          <li><?= getLink( 'About Us', '/about-us' . '' ); ?></li>
          <li class="last"><?= getLink( 'Contact Us', '/contact-us' . '' ); ?></li>
        </ul>
      </nav>

    </header>

    <div id="main" role="main">

<?php $this->get_content(); ?>

    </div>
    <footer id="main">
      <div class="fourcolumns">
        <div class="column span-2">
          <p>&copy;2012 Liriope Framework
        </div>
        <div class="column">
        <br>
        </div>
        <div class="column last">
          <nav id="footer" class="menu">
            <ul>
              <li><?= getLink( 'Work', '/work' . '' ); ?></li>
              <li><?= getLink( 'People', '/people' . '' ); ?></li>
              <li><?= getLink( 'About Us', '/about-us' . '' ); ?></li>
              <li class="last"><?= getLink( 'Contact Us', '/contact-us' . '' ); ?></li>
            </ul>
          </nav>
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

