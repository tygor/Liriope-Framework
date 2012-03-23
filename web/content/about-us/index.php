<?php
/**
 * About > index.php
 * --------------------------------------------------
 */

// page configuration
// ------------------

page::set( 'page.title', 'About Us');
$tumblrStream = 'http://jmcopeconstruction.tumblr.com/api/read';
$tumblr = new TumblrModel( $tumblrStream );
$tumblr->set( 'start', 0 )->set( 'num', 5 );

?>

<h1>About Us</h1>

<aside>
  <header>
    <h1>Tumblr</h1>
  </header>
  <ul class="tumblr-list">
    <?php foreach( $tumblr->get() as $post ): ?>
    <li><?= $post ?></li>
    <?php endforeach; ?>
  </ul>
  <footer>
    <p><a href="<?= $tumblr->href; ?>" target="_blank">Visit my <?= $tumblr->title; ?> Tumblr page</a></p>
  </footer>
</aside>
