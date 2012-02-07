<?php
/**
 * Home.php
 * --------------------------------------------------
 * homepage
 */

// page configuration
// ------------------

page::addStylesheet( 'css/style.css' );
page::addStylesheet( 'css/style.less', 'stylesheet/less' );
page::addScript( 'js/orbit.js' );

?>

<?php snippet( 'slider' ); ?>

<article>
  <header>
    <h1>Header 1</h1>
  </header>

  <p>Lorem ipsum delor sit amet conseceteur.</p>

  <footer>
  </footer>
</article>
