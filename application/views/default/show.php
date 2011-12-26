<?= $header; ?>
<?= $slider; ?>

<article>
  <header>
    <h1>Liriope Framework</h1>
    <p>Written by: Tyler</p>
  </header>

  <p>The Liriope Framework is a home grown framework. I wanted to manage a site with
  plain old files; not Wordpress, not Joomla, or anything like those. Since I had some
  past work with Symfony, I wanted a MVC structure, but I didn't want the complexity of
  Symfony.</p>

  <footer>
    <ul class="tags">
      <li>Home page</li>
      <li>article</li>
    </ul>
  </footer>
</article>

<div class='threecolumns'>

  <div class='column'>
    <p>Content 1</p>
  </div><!-- /column -->
  <div class='column'>
    <p>Content 2</p>
  </div><!-- /column -->
  <div class='column last'>
    <p>Content 3</p>
  </div><!-- /column -->

  <div class='column'>
    <p>Content 1</p>
  </div><!-- /column -->
  <div class='column'>
    <p>Content 2</p>
  </div><!-- /column -->
  <div class='column last'>
    <p>Content 3</p>
  </div><!-- /column -->

</div><!-- /threecolumns -->

<?= $footer; ?>
