<?php

use Liriope\Models\Yaml;

$page->title='Drop Box';
$page->data='2012-11-24';

$file = 'https://dl.dropbox.com/u/51310480/DropboxTest.txt';
$dropbox = new Dropbox($file);

$yml = new Yaml($dropbox);
$data = $yml->parse();

?>

<h1><?= $page->title ?></h1>

<p>This list is edited through a file that lives in a public Drop Box folder. So I can edit anywhere I have Drop Box
or web access to Drop Box and the page will updated.</p>

<ul>
<?php foreach(array_shift($data) as $item): ?>
    <li>
        <p>
            <a href="<?= $item['link'] ?>" target="_blank"><strong><?= $item['title'] ?></strong></a><br>
            <time><?= $item['pubdate'] ?></time><br>
            <?= $item['excerpt'] ?>
        </p>
    </li>
<?php endforeach ?>
</ul>
