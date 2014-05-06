<?php

// Home

$page->title = 'Home';
$page->keywords = 'Liriope,CMS,file,based,content,management';

?>

<section id="welcome">
  <hgroup>
    <h1>Liriope is a CMS</h1>
    <p class="drophead">Play with folders and files. Make a website.</p>
  </hgroup>

  <div class="row">
    <div class="four columns">
      <p><strong>You already know how it works.</strong></p>
      <p>Liriope is a file-based CMS. Simply add folders, then text files and you have a new page.</p>
    </div>
    <div class="four columns">
      <p><strong>One install, multiple sites.</strong></p>
      <p>Liriope is designed to power multiple sites. Install it in the root, and then all of the sites on your host
      can utilize the same “brains.”</p>
    </div>
    <div class="four columns">
      <p><strong>Built by a web guy.</strong></p>
      <p>Why Liriope? It&rsquo;s simple. I built it for me, and found that it could work well for anyone. Do you know HTML, PHP? Then it&rsquo;s for you too.</p>
    </div>
  </div>
</section>

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
      <?php
        $pubStart = 'Jul 5, 2013, 8:45 PM';
        $pubStop = 'Jul 5, 2013, 8:53 PM';
      ?>
      <p>Current Time: <?= date('Y-m-d H:i:s', time()) ?></p>
      <?php if( publish( $pubStart, $pubStop )): ?>
      <p>This is a time sensative message. Using the publish() function, I will only show if the date range is satisfied.<br>
        <em>This date today is between <?= $pubStart ?> and <?= $pubStop ?>.</em>
      </p>
      <?php endif ?>
      <?php if( publish( $pubStart )): ?>
      <p>This paragraph will display after the start date has passed.<br>
        <em>Today must be later than <?= $pubStart ?>.</em>
      </p>
      <?php endif ?>
      <?php if( publish( '', $pubStop )): ?>
      <p>This paragraph will display until the end date has passed.<br>
        <em>Today must be before <?= $pubStop ?></em>
      </p>
      <?php endif ?>
    </div>
  </div>
</footer>
