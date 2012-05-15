<?php
// Features > index.php
// --------------------------------------------------

$page->title = 'Features | Liriope';

?>

<article>
  <h1>Features</h1>
  <p>As I've been working with Liriope, I've already begun to develop &ldquo;Freatures&rdquo; that can be used in page. Below are some samples.</p>
</article>

<article>
  <header>
    <h1>Slider</h1>
  </header>
  <?php snippet( 'cope-slider.php' ); ?>
  <footer>
    <p>The ZURB Orbit slider can be found <a href="http://www.zurb.com/playground/orbit-jquery-image-slider">here</a> although it has been since included in the Foundation framework</p>
  </footer>
<article>
  <header>
    <h1>Tumblr</h1>
  </header>
  <p>For one project, they wanted to be able to include their Tumblr feed. Simple enough. I went to find the Tumblr API and started grabbing the resulting XML. Sure, I haven't coded the complete API interface, and I have work to do on the Tumblr Model, but it's working. Here it is in action:</p>
  <?php
  // --------------------------------------------------
    $tumblrStream = 'http://jmcopeconstruction.tumblr.com/api/read';
    $tumblr = new Tumblr( $tumblrStream );
    $tumblr->set( 'start', 0 )->set( 'num', 5 );
  // --------------------------------------------------
  ?>
  <ul class="tumblr-list">
    <?php foreach( $tumblr->get() as $post ): ?>
    <li><?= $post ?></li>
    <?php endforeach; ?>
  </ul>
  <footer>
    <p><a href="<?= $tumblr->href; ?>" target="_blank">Visit my <?= $tumblr->title; ?> Tumblr page</a></p>
  </footer>
</article>

