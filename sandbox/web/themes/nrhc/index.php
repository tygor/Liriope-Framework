<?php
/**
 * NRHC theme
 */

  $page->css( theme::$folder . '/styles/base.less', 'stylesheet/less' );
  $page->js( 'js/libs/less-1.3.0.min.js' );
  $page->js( theme::$folder . '/js/jquery.hoverIntent.minified.js' );
  $page->js( theme::$folder . '/js/nrhcTemplateSpice.js' );
  $page->script( 'less.watch();' );

  echo $site->DOCTYPE();
?>
<html class="no-js" lang="en">
<head>
  <meta charset="utf-8">

  <!-- Use the .htaccess and remove these lines to avoid edge case issues.
       More info: h5bp.com/b/378 -->
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

  <title><?= $page->title(); ?></title>
  <meta name="description" content="<?= $page->description(); ?>">
  <meta name="author" content="<?= $page->author(); ?>">

  <!-- Mobile viewport optimized: j.mp/bplateviewport -->
  <meta name="viewport" content="width=device-width,initial-scale=1">

  <link rel="shortcut icon" href="/<?= theme::$folder; ?>/images/favicon.ico">

  <!-- CSS: implied media=all -->
  <?php foreach( (array) $page->get( 'css' ) as $css ): ?>
  <link href="/<?= $css['file']; ?>" rel="<?= $css['rel']; ?>">
  <?php endforeach; ?>
  <?php foreach( (array) $page->get( 'js' ) as $script): ?>
  <script src="/<?= $script['file']; ?>" type="<?= $script['type']; ?>"></script>
  <?php endforeach; ?>
  <?php foreach( (array) $page->get( 'script' ) as $block): ?>
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

<body class="<?= browser::getBodyClass(); ?> <?= browser::areWeHome(); ?>">

  <header id="global-header" class="globalwidth">

    <h1 id="logo">
      <a href="<?php echo url() ?>" title="North Rock Hill Church">
        <span>North Rock Hill Church</span>
      </a>
    </h1><!-- /logo -->

    <nav id="global-nav">
      <?php snippet( 'navigation.php' ); ?>
    </nav><!-- /global-nav -->

  </header><!-- /global-header -->

  <section id="crown" class="globalwidth">
    <?php snippet('submenu.php', array( 'page'=>$page->url )) ?>
  </section>

  <div id="cms" role="main" class="globalwidth">
    <section id="content" class="shadow rounded">

<?= $page->render(); ?>

    </section>
  </div>

  <footer id="main" class="globalwidth">
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
          <?php snippet( 'navigation.php' ); ?>
        </nav>
      </div>
    </div>
  </footer>

  <?php if( $error ) echo $error; ?>

  <!-- JavaScript at the bottom for fast page loading -->

  <!-- Grab Google CDN's jQuery, with a protocol relative URL; fall back to local if offline -->
  <script src="//ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
  <script>window.jQuery || document.write('<script src="js/libs/jquery-1.6.2.min.js"><\/script>')</script>

  <!-- scripts concatenated and minified via ant build script-->
  <script defer src="js/plugins.js"></script>
  <script defer src="js/script.js"></script>
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

