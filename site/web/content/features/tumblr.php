<?php
$page->title = 'Tumblr API';
?>
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

