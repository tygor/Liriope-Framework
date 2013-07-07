<?php
if(!isset($idPrefix)) $idPrefix = '';
if(isset($autofocus) && $autofocus) $autofocus = 'autofocus';
else $autofocus = '';
?>
<form method="GET" action="<?= url( 'search' ) ?>" class="search-form">
  <input id="<?= $idPrefix ?>search-input" class="searchbox" type="text" name="q" <?= $autofocus ?> required placeholder="Search" autocomplete="off">
  <input class="submitbutton" type="submit">
</form>
