<?php
  $search = $page->search();
?>

<div clss="search-results">
  <h1>Search results</h1>
  <?php snippet( 'searchform.php', array('idPrefix'=>'results-', 'autofocus'=>true) ) ?>
  <hr>
  <p>Results for: <strong><?php echo $search->words() ?></strong></p>
  <ol>
  <?php foreach( $search->results() as $id => $result ): ?>
    <li> 
      <p class="title"><a href="<?php echo url( $id ) ?>">
        <?php echo $result['title'] ?>
      </a></p>
      <p class="url"><?php echo url( $id ) ?>
        <abbr class="score" title="score: <?php echo $result['score'] ?>">+</abbr>
      </p>
      <?php if(isset($result['excerpt'])): ?>
      <p class="excerpt"><?php echo $result['excerpt'] ?></p>
      <?php endif; ?>
    </li>
  <?php endforeach ?>
  </ol>
  <hr>
  <p><?php echo $search->found() ?> page<?php if( $search->found() > 1 ) echo 's' ?> found.</p>
  <p class="footnote">The search took <?php echo $search->duration() ?> seconds.</p>
</div>
