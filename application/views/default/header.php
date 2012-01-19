<?php
/**
 * Header.php
 * default header
 */
  echo $DOCTYPE;
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

  <title><?= $pageTitle; ?></title>
  <meta name="description" content="">
  <meta name="author" content="">

  <!-- Mobile viewport optimized: j.mp/bplateviewport -->
  <meta name="viewport" content="width=device-width,initial-scale=1">

  <!-- Place favicon.ico and apple-touch-icon.png in the root directory: mathiasbynens.be/notes/touch-icons -->

  <!-- CSS: implied media=all -->
  <!-- CSS concatenated and minified via ant build script-->
  <link rel="stylesheet" href="/css/style.css">
  <link rel="stylesheet/less" href="/css/style.less">
  <script src="js/libs/less-1.1.5.min.js" type="text/javascript"></script>
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
          <img src="/images/cope-logo.png" alt="J. M. Cope Construction Management" height="48" width="199">
        </a>
      </hgroup>

      <nav id="main" class="menu">
        <?php snippet( 'default/navigation' ); ?>
      </nav>

    </header>

    <div id="main" role="main">
