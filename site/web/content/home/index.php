<?php
// Home

$page->title = 'Liriope | Monkey Grass';

?>

<section id="welcome">
  <h1>Welcome</h1>
  <p>I started the Liriope Framework to learn more about PHP and at the same time build my own framework. Along the way, I realized that this was becoming something useful for various websites I was thinking about or working on. I love it when your interests match up with your workload!</p>
  <p>This project is on GitHub, and I would love to include others in this learnining project. Please feel free to contribute. Find me on <a href="https://github.com/tygor/Liriope-Framework" target="_blank">GitHub</a>! </p>
</section>

<?php snippet( 'searchform.php' ) ?>

<footer>
  <div class="threecolumns">
    <div class="column">
      <h2>Blog Articles</h2>
      <?php echo module( 'blog', 'show', array( 'limit' => 4 )) ?>
    </div>
    <div class="column">
      <h2>Column</h2>
    </div>
    <div class="column last">
      <h2>Column</h2>
    </div>
  </div>
</footer>
