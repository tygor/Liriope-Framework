<?php
// searchform.php
// A snippet to place a search form on the page
// 
// @param  string  $idPrefix  The prefix to place on the id tags of the form so that multiple instance can be used.
// @param  boolean $autofocus Whether or not autofocus on the search form input field should be used.
//

// Define a default empty string if $idPrefix isn't passed
if(!isset($idPrefix)) $idPrefix = '';
// Build the autofocus attribute string based on the passed variable
if(isset($autofocus) && $autofocus) $autofocus = 'autofocus';
else $autofocus = '';

?>
<form method="GET" action="<?= url( 'search' ) ?>" class="search-form">
  <input id="<?= $idPrefix ?>search-input" class="searchbox" type="text" name="q" <?= $autofocus ?> required placeholder="Search" autocomplete="off">
  <input class="submitbutton" type="submit">
</form>
