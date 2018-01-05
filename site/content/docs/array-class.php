<?php

$page->title = "Array class";
$page->date = '2012-07-06';

?>

<h1><?php echo $page->title() ?></h1>
<hr class="readmore">

<h1><?php echo $page->title() ?></h1>
<p>The array class&mdash;'a' for short&mdash;handles common array prodedures.</p>

<h2>a::get( $array, $key, $default=NULL )</h2>
<p>Returns a value from the array based on the passed key.</p>
<dl>
  <dt><em>(array)</em> $array</dt>
  <dd>the array to get a value from</dd>
  <dt><em>(mixed)</em> $key</dt>
  <dd>the key to seek from the array</dd>
  <dt><em>(mixed)</em> $default</dt>
  <dd>if no value was found, this value is returned instead</dd>
</dl>

<h2>a::getAll( $array, $keys )</h2>
<p>Returns multiple values from an array.</p>
<dl>
  <dt><em>(array)</em> $array</dt>
  <dd>the array to get values from</dd>
  <dt><em>(mixed)</em> $keys</dt>
  <dd>the keys to seek from the array</dd>
</dl>

<h2>a::show( $array, $print=TRUE )</h2>
<p>Either returns or echos the array wrapped in &lt;pre&gt; tags. Useful for debugging.</p>
<dl>
  <dt><em>(array)</em> $array</dt>
  <dd>the array to display</dd>
  <dt><em>(bool)</em> $print</dt>
  <dd>TRUE echos the array after a bit of cleaning wrapped in &lt;pre&gt; tags<br>FALSE returns the value</dd>
</dl>

<h2>a::search( $array, $search )</h2>
<p>Searches an array for the passed value using regular expression.</p>
<dl>
  <dt><em>(array)</em> $array</dt>
  <dd>the array to search within</dd>
  <dt><em>(mixed)</em> $search</dt>
  <dd>The search subject. Either a string, integer, or an unquoted regular expression will do.</dd>
</dl>

<h2>a::searchKeys( $array, $search )</h2>
<p>Searches for a key in an array by regular expression.</p>
<dl>
  <dt><em>(array)</em> $array</dt>
  <dd>the array to search within</dd>
  <dt><em>(mixed)</em> $search</dt>
  <dd>The search subject. Either a string, integer, or an unquoted regular expression will do.</dd>
</dl>

<h2>a::contains( $array, $search )</h2>
<p>Returns TRUE or FALSE if the array contains the passed value.</p>
<dl>
  <dt><em>(array)</em> $array</dt>
  <dd>The array to look within</dd>
  <dt><em>(mixed)</em> $search</dt>
  <dd>The search subject. Either a string, integer, or an unquoted regular expression will do.</dd>
</dl>

<h2>a::first( $array )</h2>
<p>Returns the first element of an array. Non-destructive (the passed array remains intact).</p>
<dl>
  <dt><em>(array)</em> $array</dt>
  <dd>the array to pull from</dd>
</dl>

<h2>a::last( $array )</h2>
<p>Returns the last element of an array. Non-destructive (the passed array remains intact).</p>
<dl>
  <dt><em>(array)</em> $array</dt>
  <dd>the array to pull from</dd>
</dl>

<h2>a::glue( $array, $string=NULL )</h2>
<p>Glues the passed array into a string using a string as the glue.</p>
<dl>
  <dt><em>(array)</em> $array</dt>
  <dd>the array to glue together</dd>
  <dt><em>(string)</em> $string</dt>
  <dd>the glue to use between each item in the array</dd>
</dl>

<h2>a::combine()</h2>
<p>Uses <code>func_get_args()</code> to combines the passed arguments into one array and returns them.</p>

<h2>a::toObject( $array )</h2>
<p>Converts the passed associative array into an object.</p>
<dl>
  <dt><em>(array)</em> $array</dt>
  <dd>The array to traverse recursively</dd>
</dl>

<h2>a::rewind( $array )</h2>
<p>Rewinds the given array to its first element.</p>
<dl>
  <dt><em>(array)</em> $array</dt>
  <dd>the array to rewind</dd>
</dl>
<p class="TODO">I am not sure that this function works as is because it is called statically. Perhaps if I use the symbolic link '&amp;' character?</p>

