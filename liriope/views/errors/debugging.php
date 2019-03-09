<?php

// Debugging

?>

<section id="debugging">

<h1>Debugging <a href="#" onclick="toggle_visibility('debug-content');return false;">Show/Hide</a></h1>

<ol id="debug-content">
<?php foreach( LiriopeError::get() as $k => $error ): ?>
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

</section><!-- /#debugging -->

<script type="text/javascript">
<!--
  function toggle_visibility(id) {
    var e = document.getElementById(id);
    if( e.style.display == 'block' )
      e.style.display = 'none';
    else
      e.style.display = 'block';
  }
//-->
</script>
