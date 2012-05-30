<ul class="menu">
<?php foreach( $module->posts as $blog ): ?>
  <li><a href="<?php echo $blog->url() ?>"><?php echo $blog->title() ?></a></li>
<?php endforeach ?>
</ul>
<p><a href="<?php echo url( '/blog' ) ?>">Read more news&hellip;</a></p>

