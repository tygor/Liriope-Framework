<?php
/**
 * NRHC theme
 */

  // for the Orbit slider
  $page->css( 'plugins/orbit/orbit-1.3.0.css' );
  $page->js( 'plugins/orbit/jquery.orbit-1.3.0.js' );

  $page->css( theme::$folder . '/styles/base.less', 'stylesheet/less' );
  $page->js( 'js/libs/less-1.3.0.min.js' );
  $page->js( theme::$folder . '/js/jquery.hoverIntent.minified.js' );
  $page->js( theme::$folder . '/js/nrhcTemplateSpice.js' );
  $page->js( theme::$folder . '/js/plugins.js' );
  $page->script( 'less.watch();' );

  echo $site->DOCTYPE();
?>
<html class="no-js" lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

  <title><?php echo $page->title() ?> | <?php echo $site->title() ?></title>

  <meta name="description" content="<?= $page->description(); ?>">
  <meta name="author" content="<?= $page->author(); ?>">
  <meta name="viewport" content="width=device-width,initial-scale=1">

  <link rel="shortcut icon" href="/<?= theme::$folder; ?>/images/favicon.png">

  <?php foreach( (array) $page->get( 'css' ) as $css ): ?>
  <link href="<?= $css['file']; ?>" rel="<?= $css['rel']; ?>">
  <?php endforeach; ?>

  <script src="/js/libs/modernizr-2.0.6.min.js"></script>
</head>

<body class="<?= browser::getBodyClass(); ?> <?= browser::areWeHome(); ?>">

  <header id="global-header" class="globalwidth">

    <h1 id="logo">
      <a href="<?php echo url() ?>" title="North Rock Hill Church">
        <span>North Rock Hill Church</span>
      </a>
    </h1><!-- /logo -->

    <a href="#" id="global-nav-expand">Expand Icon</a>
    <nav id="global-nav">
      <?php snippet( 'menu.php' ); ?>
    </nav><!-- /global-nav -->

  </header><!-- /global-header -->

  <section id="crown" class="globalwidth">
    <?php snippet('submenu.php', array( 'page'=>$page->url )) ?>
    <?php snippet( 'slider.php' ) ?>
  </section>

  <div id="cms" role="main" class="globalwidth">
    <section id="content" class="shadow rounded">

<?= $page->render(); ?>

    </section>
  </div>

  <footer id="global-footer" class="globalwidth">
    <div class="fourcolumns">
      <div class="column span-3">
        <nav id="footer-menu">
          <?php snippet( 'menu.php' ) ?>
        </nav>
        <p>&copy;<?php echo date( 'Y', time()) ?> North Rock Hill Church</p>
        <p>P.O. Box 2477<br>
        2562 Mt. Gallant Rd.<br>
        Rock Hill, SC 29732<br>
        p: 803-327-6753<br>
        <a href="mailto:info@northrockhill.com">info@northrockhill.com</a></p>
      </div>
      <div class="column last">
        <a href="http://twitter.com/nrhc" target="_blank"><img src="/<?php echo theme::$folder ?>/images/icons/twitter_32.png" alt="@nrhc on Twitter"></a>
        <a href="http://www.facebook.com/northrockhillchurch" target="_blank"><img src="/<?php echo theme::$folder ?>/images/icons/facebook_32.png" alt="northrockhillchurch on Facebook"></a>
        <a href="http://vimeo.com/nrhc" target="_blank"><img src="/<?php echo theme::$folder ?>/images/icons/vimeo_32.png" alt="NRHC on Vimeo"></a>
      </div>
    </div>
  </footer>

  <?php if( $error ) echo $error; ?>

  <!-- JavaScript at the bottom for fast page loading -->

  <!-- Grab Google CDN's jQuery, with a protocol relative URL; fall back to local if offline -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
  <script>window.jQuery || document.write('<script src="js/libs/jquery.min.js"><\/script>')</script>

  <!-- scripts concatenated and minified via ant build script-->
  <script defer src="js/plugins.js"></script>
  <script defer src="js/script.js"></script>
  <?php foreach( (array) $page->get( 'js' ) as $script): ?>
  <script src="/<?= $script['file']; ?>" type="<?= $script['type']; ?>"></script>
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

