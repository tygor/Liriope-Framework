<?php

// Debugging

?>

<div id="debugging">

<h1>Debugging</h1>

<ol>
<?php foreach( error::get() as $k => $error ): ?>
  <?php if( !is_array( $error )): ?>
  <li><?= $error ?></li>
  <?php else: ?>
  <li>
    <?php if( $c = a::get( $error, 'code' )): ?>
    <?php if( $s = a::get( $error, 'codeString' )): ?>
    <b><?= $s ?></b>
    <?php endif; ?>
    (code <?= $c ?>)
    <br>
    <?php endif; ?>
    <?php if( $m = a::get( $error, 'msg' )): ?>
    <?= $m ?><br>
    <?php endif; ?>
    <?php $f = a::get( $error, 'file' ); $l = a::get( $error, 'line' );
    if( $f && $l ): ?>
    <em>(<?= $f ?>, line <?= $l ?>)</em>
    <?php endif; ?>
  </li>
  <?php endif; ?>
<?php endforeach; ?>
</ol>

</div><!-- /#debugging -->
