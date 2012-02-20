<?php
/**
 * cope.php
 * J. M. Cope theme
 */
  echo page::get( 'page.DOCTYPE' );
?>
<html class="no-js" lang="en">
<head>
  <meta charset="utf-8">

  <!-- Use the .htaccess and remove these lines to avoid edge case issues.
       More info: h5bp.com/b/378 -->
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

  <title><?= page::get( 'page.title' ); ?></title>
  <meta name="description" content="<?= page::get( 'page.description', 'Liriope Framework default theme titled Grass.' ) ?>">
  <meta name="author" content="<?= page::get( 'page.author', 'Tyler Gordon' ) ?>">

  <!-- Mobile viewport optimized: j.mp/bplateviewport -->
  <meta name="viewport" content="width=device-width,initial-scale=1">

  <!-- Place favicon.ico and apple-touch-icon.png in the root directory: mathiasbynens.be/notes/touch-icons -->

  <!-- CSS: implied media=all -->
  <?php foreach( page::getStylesheets() as $css ): ?>
  <link href="/<?= $css['file']; ?>" rel="<?= $css['rel']; ?>">
  <?php endforeach; ?>
  <?php foreach( page::getScripts() as $script): ?>
  <script src="/<?= $script['file']; ?>" type="<?= $script['type']; ?>"></script>
  <?php endforeach; ?>
  <?php foreach( page::getScriptBlocks() as $block): ?>
  <script type="text/javascript" charset="UTF-8">
    <?= $block; ?>
  </script>
  <?php endforeach; ?>
  <!-- end CSS-->

  <!-- More ideas for your <head> here: h5bp.com/d/head-Tips -->

  <!-- All JavaScript at the bottom, except for Modernizr / Respond.
       Modernizr enables HTML5 elements & feature detects; Respond is a polyfill for min/max-width CSS3 Media Queries
       For optimal performance, use a custom Modernizr build: www.modernizr.com/download/ -->
  <script src="/js/libs/modernizr-2.0.6.min.js"></script>
</head>

<body class="<?= browser::getBodyClass(); ?>">

  <div id="container">
    <header id="main" class="clear">

      <hgroup id="identity">
        <a href="/">
          <img src="/<?= theme::folder(); ?>/images/cope-logo.png" alt="Logo" height="50" width="208">
        </a>
      </hgroup>

      <nav id="main" class="menu">
        <?php snippet( 'navigation.php' ); ?>
      </nav>

    </header>

    <div id="main" role="main">

<?= page::getContent(); ?>

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
            <?php snippet( 'navigation.php' ); ?>
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

