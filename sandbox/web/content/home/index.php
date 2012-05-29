<?php
// Home

$page->title = 'Home';

?>

<header>

  <div class="threecolumns">

    <div class="column teaser">
      <h3>News</h3>
      <ul class="menu teaser">
      <?php foreach( module( 'blog', 'show', array( 'dir' => 'news' )) as $news ) : ?>
        <li><a href="<?php echo $news->url() ?>"><?php echo $news->title() ?></a></li>
      <?php endforeach ?>
      </ul>
      <p><a href="<?php echo url( '/news' ) ?>">More News&hellip;</a></p>
    </div>

    <div class="column teaser">
      <article>
        <h3><a href="<?php echo url( 'news/discover_2012-04-30' ) ?>">Discover NRHC</a></h3>
        <a href="<?php echo url( 'news/discover_2012-04-30' ) ?>">
          <img src="http://nrhc-pages.northrockhill.com/discover-class/Discover-Chip.png" alt="Discover class" width="100" height="100" class="alignleft chip">
        </a>
        <p>Learn about NRHC! Dinner and childcare are provided.</p>
        <p>The next Discover class is on<br><span class="b size-large">Jun 10<sup>th</sup></span></p>
      </article>
    </div>

    <div class="column teaser last">
      <article>
        <h3><a href="<?php echo url( '/media/sent_2012-05-20' ) ?>">Current Series</a></h3>
        <a href="<?php echo url( '/media/sent_2012-05-20' ) ?>">
          <img src="http://nrhc-pages.northrockhill.com/messages/2012/sent/Sent-Web-Chip.png" alt="Sent series" width="100" height="100" class="chip alignleft">
        </a>
        <p>The Gospel. Is it religious advice to follow? A prayer to pray? The diving board we launch from or the ocean we swim in?</p>
      </article>
    </div>

  </div>

</header>
