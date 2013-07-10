<?php
// _search_autocomplete.php
// The autocomplete view that appears below the search input box
$first = true;
?>
<ul>
    <?php foreach($module->guesses as $item => $v): ?>
    <li <?php if($first): ?>class="active"<?php $first=0; endif ?>>
        <a href="/search?q=<?= $item ?>"><?= $item ?></a>
    </li>
    <?php endforeach ?>
</ul>
