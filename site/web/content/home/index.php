<?php
// Home

$page->title = 'Home';

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
      <h2>Docs</h2>
      <?php echo module( 'blog', 'show', array(
        'dir' => 'docs',
        'limit' => 10
      )) ?>
    </div>
    <div class="column last">
      <h2>Timed content</h2>
      <?php if( publish( 'Aug 10, 2012, 8:00 AM', 'Aug 20, 2012, 8:54 AM' )): ?>
      <p>This is a time sensative message. Using the publish() function, I will only show if the date range is satisfied.<br>
        <em>This date today is between Aug 10 and Aug 20.</em>
      </p>
      <?php endif ?>
      <?php if( publish( 'Aug 10, 2012, 8:00 AM' )): ?>
      <p>This paragraph will display after the start date has passed.<br>
        <em>Today must be later than Aug 10.</em>
      </p>
      <?php endif ?>
      <?php if( publish( '', 'Aug 20, 2012, 8:54 AM' )): ?>
      <p>This paragraph will display until the end date has passed.<br>
        <em>Today must be before Aug 20</em>
      </p>
      <?php endif ?>
    </div>
  </div>
</footer>
