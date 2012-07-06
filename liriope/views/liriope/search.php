<?php
  $search = $page->search();
?>

<div id="searchresults">
  <h1>Search results</h1>
  <?php snippet( 'searchform.php' ) ?>
  <hr>
  <p>Results for: <strong><?php echo $search->words() ?></strong></p>
  <ol>
  <?php foreach( $search->results() as $id => $result ): ?>
    <li> 
      <a class="title" href="<?php echo url( $id ) ?>">
        <?php echo $result['title'] ?>
      </a><br>
      score: <?php echo $result['count'] ?> &mdash;
      <?php echo url( $id ) ?>
    </li>
  <?php endforeach ?>
  </ol>
  <hr>
  <p><?php echo $search->found() ?> page<?php if( $search->found() > 1 ) echo 's' ?> found.</p>
  <p class="footnote">The search took <?php echo $search->duration() ?> seconds.</p>
</div>
