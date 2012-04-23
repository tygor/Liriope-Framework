<?php

// Debugging

?>

<div id="debugging">

<h1>Debugging</h1>

<?php foreach( error::get() as $error ): ?>
  <?php var_dump($error); ?><br>
<?php endforeach; ?>

</div><!-- /#debugging -->
