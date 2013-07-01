<ul class="menu">
<?php foreach( $module->posts as $blog ): ?>
  <li><time><?php echo date( 'M, j', $blog->time()) ?></time> <a href="<?php echo $blog->url() ?>"><?php echo $blog->title() ?></a></li>
<?php endforeach ?>
</ul>
<p><a href="<?php echo url( $module->more ) ?>">Read more articles</a></p>

