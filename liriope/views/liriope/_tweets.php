<?php
//
// Tweets Module partial
// receives an array of tweets
//
?>
<?php foreach($module->tweets as $tweet): ?>
<p>
  <a href="http://twitter.com/<?= $tweet['user']['screen_name'] ?>" target="_blank">
    @<?= $tweet['user']['screen_name'] ?>
  </a>
  <?= str::linkLinks($tweet['text']) ?>
</p>
<?php endforeach ?>
