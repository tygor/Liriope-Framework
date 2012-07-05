<?php
// paper theme

  $page->css( theme::$folder . '/style.css' );

  echo $site->DOCTYPE();
?>
<html class="no-js" lang="en">

<head>
  <meta charset="utf-8">

  <title><?= $page->title(); ?></title>
  <meta name="description" content="<?= $page->description(); ?>">
  <meta name="author" content="<?= $page->author(); ?>">

  <link rel="shortcut icon" href="/<?= theme::$folder; ?>/images/favicon.ico">

  <!-- CSS -->
  <?php foreach( (array) $page->get( 'css' ) as $css ): ?>
  <link href="<?= $css['file']; ?>" rel="<?= $css['rel']; ?>">
  <?php endforeach; ?>

  <!-- JS -->
  <?php foreach( (array) $page->get( 'js' ) as $script): ?>
  <script src="<?= $script['file']; ?>" type="<?= $script['type']; ?>"></script>
  <?php endforeach; ?>
  
  <?php foreach( (array) $page->get( 'script' ) as $block): ?>
  <script type="text/javascript" charset="UTF-8">
    <?= $block; ?>
  </script>
  <?php endforeach; ?>
  <!-- end CSS-->

  <script src="/js/libs/modernizr-2.0.6.min.js"></script>
</head>

<body class="<?= browser::getBodyClass(); ?> <?= browser::areWeHome(); ?>">

  <header id="main">

    <hgroup id="identity">
      <a href="/">
        <img src="/<?= theme::$folder; ?>/images/liriope-logo.png" alt="Logo" height="75" width="200">
      </a>
    </hgroup>

    <nav id="main" class="menu punchcard">
      <?php snippet( 'navigation.php' ) ?>
    </nav>

  </header>

  <div id="main" role="main">

<?= $page->render(); ?>

  </div>

  <footer id="main">
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
          <?php snippet( 'navigation.php' ) ?>
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
  <script defer src="/js/plugins.js"></script>
  <script defer src="/js/script.js"></script>
  <!-- end scripts-->

</body>
</html>

