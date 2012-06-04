<?php
$page->title = "RegEx Routes?";
$page->date = '2012/06/03';
?>

<h1><?= $page->title() ?></h1>
<hr class="readmore">

<h1><?= $page->title() ?></h1>
<p>I think it's time to put some more attention on the router. I think it's a weak spot even though it works.</p>
<p>Right now, there needs to be about 2 rules for each route. One that is the route without a wildcard, and then one that has a wildcard at the end. I think that the reason that this error exists is that I don't allow for the wildcard to be empty.</p>
<p>I want to leave the need for a wildcard though. I want the ability for a route to not pass if there is trailing params. So I will leave the wildcard idea in place. But to simplify, wilds can be empty.</p>
<p>Another thing that I really would love to have in place is the ability to have named wildcards. These could follow any existing convention like appending a colon to the name (:name). This way, I wouldn't need name/value pairs in my URLs.</p>
<p>Currently:<br>
  <ul>
    <li>URL: <code>http://site.com/controller/action/name/value/name/value/name/value</code></li>
    <li>Rule: <code>controller/action/*</code></li>
    <li>Route: <code>Controller::action( name=>value, name=>value, name=>value )</code></li>
  </ul>
</p>
<p>Desired:<br>
  <ul>
    <li>URL: <code>http://site.com/controller/action/value/value/value</code></li>
    <li>Rule: <code>controller/action/:limit/:sort/:page</code></li>
    <li>Route: <code>Controller::action( limit=>value, sort=>value, page=>value )</code></li>
  </ul>
</p>
