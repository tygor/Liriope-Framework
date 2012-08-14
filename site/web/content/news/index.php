<h1>News</h1>
<p>This is a test for a nested blog. So far, blogs function if they are a root folder, but didn't work so well if they were nested.</p>
<p>So why chickens? I have chickens. Why kittens? Because they are more interesting that <a href="http://placehold.it">http://placehold.it</a> images.</p>
<p>
  <a href="<?= url( 'news/chicken' ) ?>">Chicken News</a>
</p>
<?php echo module( 'blog', 'show', array( 'dir' => 'news:chicken' )) ?>
