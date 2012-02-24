<?php
/**
 * Home.php
 * --------------------------------------------------
 * homepage
 */

// page configuration
// ------------------

page::set( 'page.title', 'Homepage');
$tumblr = new TumblrModel('http://jmcopeconstruction.tumblr.com/api/read', true);
$tumblr->set( 'start', 0 )->set( 'num', 5 );

?>

<?php snippet( 'cope-slider.php' ); ?>

<aside>
  <header>
    <h1>Tumblr</h1>
  </header>
  <ul class="tumblr-list">
    <?php foreach( $tumblr->get() as $post ): ?>
    <li><?= $post ?></li>
    <?php endforeach; ?>
  </ul>

</aside>

<article>
  <header>
    <h1>Homepage</h1>
  </header>

  <p>Lorem ipsum delor sit amet conseceteur.</p>

  <footer>
  </footer>
</article>
