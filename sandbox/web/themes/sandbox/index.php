<?php
/**
 * Sandbox theme
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

  <link rel="shortcut icon" href="<?= theme::folder(); ?>/images/favicon.ico">

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
</head>
<body class="<?= browser::getBodyClass(); ?> <?= browser::areWeHome(); ?>">
  <h1>Sandbox</h1>
  <hr>
<?= page::getContent(); ?>
  <hr>
</body>
</html>
