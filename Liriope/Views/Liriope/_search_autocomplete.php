<?php
// _search_autocomplete.php
// The autocomplete view that appears below the search input box
?>
<ul>
    <?php foreach($module->guesses as $item => $v): ?>
    <li><a href="/search?q=<?= $item ?>"><?= $item ?></a></li>
    <?php endforeach ?>
</ul>
