<?php
/**
 * grass.php
 * Grass theme
 */

  $page->css( '/vendors/normalize/normalize.css' );
  $page->css( '/css/styles.css' );

  $page->css( theme::$folder . '/style.css' );

  echo $site->DOCTYPE();
?>
<html class="no-js" lang="en">
<head>
  <meta charset="utf-8">

  <!-- Use the .htaccess and remove these lines to avoid edge case issues.
       More info: h5bp.com/b/378 -->
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

  <title><?= $page->title(); ?> | <?= $site->title() ?></title>
  <meta name="description" content="<?= $page->description(); ?>">
  <meta name="author" content="<?= $page->author(); ?>">
  <?php echo $page->META ?>

  <!-- Mobile viewport optimized: j.mp/bplateviewport -->
  <meta name="viewport" content="width=device-width,initial-scale=1">

  <link rel="shortcut icon" href="/<?= theme::$folder; ?>/images/favicon.ico">

  <!-- CSS: implied media=all -->
  <?php foreach( (array) $page->get( 'css' ) as $css ): ?>
  <link href="<?= $css['file']; ?>" rel="<?= $css['rel']; ?>">
  <?php endforeach; ?>
  <!-- end CSS-->

  <!-- All JavaScript at the bottom, except for Modernizr / Respond.
       Modernizr enables HTML5 elements & feature detects; Respond is a polyfill for min/max-width CSS3 Media Queries
       For optimal performance, use a custom Modernizr build: www.modernizr.com/download/ -->
  <script src="/js/libs/modernizr-2.0.6.min.js"></script>
</head>

<body class="<?= browser::getBodyClass(); ?> <?= browser::areWeHome(); ?>">
  <img id="background" src="/<?= theme::$folder; ?>/images/grass-wallpaper.jpg">

  <header id="main" class="content">

    <hgroup id="identity">
      <a href="/">
        <img src="/<?= theme::$folder; ?>/images/liriope-logo.png" alt="Logo" height="75" width="200">
      </a>
    </hgroup>

    <nav id="main" class="menu">
      <?php echo module( 'liriope', 'menu', array( 'page'=>$page )); ?>
    </nav>

  </header>

  <div id="main" role="main" class="content">

<?echo $liriope ?>

  </div><!-- /#main -->

  <footer id="footer" class="content">
    <div class="fourcolumns">
      <div class="column span-2">
        <p>View the <a href="https://github.com/tygor/Liriope-Framework" target="_blank">Liriope Framework</a> project on Github</p>
        <p>&copy;2012 Liriope Framework
      </div>
      <div class="column">
      <br>
      </div>
      <div class="column last">
        <nav id="footer" class="menu">
          <?php echo module( 'liriope', 'menu', array( 'page'=>$page, 'depth'=>1 )); ?>
        </nav>
      </div>
    </div>
  </footer><!-- /footer#main -->

  <?php if( $error ) echo $error; ?>

  <!-- JavaScript at the bottom for fast page loading -->

  <!-- Grab Google CDN's jQuery, with a protocol relative URL; fall back to local if offline -->
  <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.1/jquery.min.js"></script>
  <script>window.jQuery || document.write('<script src="js/libs/jquery.min.js"><\/script>')</script>

  <!-- scripts concatenated and minified via ant build script-->
  <script defer src="/js/plugins.js"></script>
  <script defer src="/js/script.js"></script>
<?php foreach( (array) $page->get( 'js' ) as $script): ?>
  <script defer src="<?= $script['file']; ?>" type="<?= $script['type']; ?>"></script>
<?php endforeach; ?>
<?php foreach( (array) $page->get( 'script' ) as $block): ?>
  <script type="text/javascript" charset="UTF-8">
    <?= $block; ?>
  </script>
<?php endforeach; ?>
  <!-- end scripts-->

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
</html>

