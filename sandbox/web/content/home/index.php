<?php
// Home

$page->title = 'Home';

?>

<header>

  <div class="threecolumns">

    <div class="column teaser">
      <h3>News</h3>
      <?php module( 'blog', 'show', array( 'dir' => 'news' )) ?>
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
        <h3><a href="<?php echo url( '/media/man-card_2012' ) ?>">Current Series</a></h3>
        <a href="<?php echo url( '/media/man-card_2012' ) ?>">
          <img src="http://nrhc-pages.northrockhill.com/messages/2012/man-card/Man-Card-Chip.png" alt="Sent series" width="100" height="100" class="chip alignleft">
        </a>
        <p>Have you ever lost your Man Card? Being less than a man requires surrender of your Man
        Card. It also violates your God-given purpose&hellip;</p>
      </article>
    </div>

  </div>

</header>
