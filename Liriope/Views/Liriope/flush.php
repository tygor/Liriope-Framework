<h1>Cache flushed</h1>
<?php echo( c::get( 'root.cache' ) ); ?>
<?php $cache = dir::read( c::get( 'root.cache' ) ); ?>
<p>Now the cache folder contains:</p>
<pre>
<?php var_dump($cache) ?>
</pre>
