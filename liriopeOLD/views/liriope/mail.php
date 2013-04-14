<!DOCTYPE html>
<?php

// set theme to null so the content below is all that is output
$page->theme = NULL;

?>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="refresh" content="0;URL=mailto:<?=$page->email()?>" />
    <title>Send an e-mail</title>
  </head>

  <body>
    <div id="wrap">
      <h1>You're about to send an e-mail to <strong><?php echo $page->email() ?></strong></h1>
      <p>We've opened up your e-mail program. If that didn't work, please copy and paste this email into your favorite email program:
      <a href="mailto:<? echo $page->email() ?>"><?=$page->email() ?></a>.</p>
    </div>
  </body>
</html>
