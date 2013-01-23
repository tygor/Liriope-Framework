<?php

use Liriope\Toolbox\String;

$page->title = "String model";
$page->date = '2012-09-10';

$sample = new String("The quick brown fox jumps over the lazy dog.");

?>

<h1><?= $page->title ?></h1>
<p>Create an object with your favorite string, then mess it up with these methods&hellip;</p>
<hr class="readmore">

<h1><?= $page->title ?></h1>
<p>The string object has the benefit of being chainable. This means that it returns itself at the
end of most methods (the ones that retain the string status). Also, these modifications are non-destructive
in that the original string is preserved. Once the instance has been retrieved, the object
resets to the original for further modification.</p>

<h2>new String( $the_string )</h2>
<p>First, create a new string object by assigning it to a variable</p>
<dl>
  <dt><em>(string)</em> $the_string</dt>
  <dd>The string to modify.</dd>
</dl>
<pre>$string = new String( '<?= $sample->peek() ?>' );</pre>

<hr>
<p><strong>Chainable methods</strong></p>
<p>These methods modify an instance of the original string. They do not need to be passed the object's string, and they return
the object itself so that you can chain multiple methods on one line.</p>
<pre>
$string = new String( '<?= $sample ?>' );
$string->to_lowercase()->reverse();
echo $string;

result: <?= $sample->to_lowercase()->reverse() ?>
</pre>
<hr>

<h2>to_lowercase()</h2>
<p>Changes all uppercase letters to lowercase.</p>
<pre>
$string = new String( '<?= $sample ?>' );
$string->to_lowercase();
echo $string;

result: <?= $sample->to_lowercase() ?>
</pre>

<h2>reverse()</h2>
<p>This method simply takes the characters int he string and reverses them.</p>
<pre>
$string = new String( '<?= $sample ?>' );
$string->reverse();
echo $string;

result: <?= $sample->reverse() ?>
</pre>

