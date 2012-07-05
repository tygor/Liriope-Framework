<?php
$search = $page->search();
?>
<h1>Search results</h1>
<?php snippet( 'searchform.php' ) ?>
<hr>
<ol>
<?php foreach( $search->results() as $id => $result ): ?>
  <li><a href="<?php echo url( $id ) ?>"><?php echo url( $id ) ?></a> (score: <?php echo $result ?>)</li>
<?php endforeach ?>
</ol>
<hr>
<p><?php echo $search->found() ?> page<?php if( $search->found() > 1 ) echo 's' ?> found.</p>
<p class="footnote">The search took <?php echo $search->duration() ?> seconds.</p>
