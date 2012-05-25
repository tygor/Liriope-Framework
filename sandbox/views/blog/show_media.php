<?php
/**
 * Blog/Show_Media.php
 */

$page->title = "News";

?>

<section id="blog">
  <h1>Media</h1>
  <p>Every week, we record the message on Sunday, and every week, we post these to our podcast, RSS
  feed, and website. Find our iTunes podcast, our Vimeo video messages, or simply subscribe to our
  feed directly from our website.</p>

<?php

if( !$page->error ) :
  $blogs = $page->blogs;

?>

<div class="fourcolumns">
  <?php for( $c=0; $c < count( $blogs ); $c++ ): ?>
  <div class="column<?php echo (($c+1)%3 == 0) ? ' last' : '' ?>">
    <article class="entry">
      <div>
        <?php echo html( $blogs[$c]->intro() ) ?>
        <a href="<?php echo url( $blogs[$c]->url()) ?>" class="readmore">Read More&hellip;</a>
      </div>
    </article>
  </div>
  <?php endfor ?>
</div>

<?php else: ?>
  <p>No posts were found</p>
<?php endif ?>

</section><!-- /#blog-list -->

