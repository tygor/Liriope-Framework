<?php
/**
 * Home.php
 * --------------------------------------------------
 * homepage
 */

// page configuration
// ------------------

page::set( 'page.title', 'Homepage');
$tumblrStream = 'http://jmcopeconstruction.tumblr.com/api/read';
$tumblr = new TumblrModel( $tumblrStream );
$tumblr->set( 'start', 0 )->set( 'num', 5 );

?>

<?php snippet( 'cope-slider.php' ); ?>

<div class="fourcolumns">
  <div class="column span-3">
    <article>
      <header>
        <h1>Homepage</h1>
      </header>

      <p>Lorem ipsum delor sit amet conseceteur.</p>

      <footer>
      </footer>
    </article>
  </div>

  <div class="column last">
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
  </div>
</div>
