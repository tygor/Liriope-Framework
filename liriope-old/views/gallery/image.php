<?php

$image = $page->image();
$page->title = $image->name;

?>

<h1><?php echo $image->name ?></h1>
<img src="<?php echo $image->url ?>">
<p><?php if( isset( $image->caption )) echo $image->caption ?></p>
