<?php

$page->title='Drop Box';
$page->data='2012-11-24';

$file = 'https://dl.dropbox.com/u/51310480/DropboxTest.txt';
$dropbox = new Dropbox($file);

$yml = new Yaml($dropbox);
$data = $yml->parse();

?>

<h1><?= $page->title ?></h1>

<pre>
<?php print_r($data) ?>
</pre>
