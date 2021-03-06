<?php
// paper theme

  $page->css( theme_folder() . '/style.css' );

  echo $site->DOCTYPE();
?>
<html class="no-js" lang="en">

<head>
  <meta charset="utf-8">

  <title><?= $page->title(); ?></title>
  <meta name="description" content="<?= $page->description(); ?>">
  <meta name="author" content="<?= $page->author(); ?>">

  <link rel="shortcut icon" href="<?= theme_folder() ?>/images/favicon.ico">

  <!-- CSS -->
  <?php foreach( (array) $page->get( 'css' ) as $css ): ?>
  <link href="<?= $css['file']; ?>" rel="<?= $css['rel']; ?>">
  <?php endforeach; ?>

  <!-- end CSS-->

  <script src="/js/libs/modernizr-2.0.6.min.js"></script>
</head>

<body>

  <header id="main">

    <hgroup id="identity">
      <a href="/">
        <img src="<?= theme_folder() ?>/images/Liriope-logo.gif" alt="Logo">
      </a>
    </hgroup>

    <nav id="main" class="menu">
      <?php echo module( 'liriope', 'menu', array( 'page'=>$page )); ?>
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
          <?php echo module( 'liriope', 'menu', array( 'page'=>$page )); ?>
        </nav>
      </div>
    </div>
  </footer>

  <!-- JavaScript at the bottom for fast page loading -->

  <!-- Grab Google CDN's jQuery, with a protocol relative URL; fall back to local if offline -->
  <script src="//ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
  <script>window.jQuery || document.write('<script src="js/libs/jquery-1.6.2.min.js"><\/script>')</script>

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

</body>
</html>

