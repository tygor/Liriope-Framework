<article>
  <h1>Contact us</h1>

  <div class='twocolumns'>

    <div class='column'>
      <?php foreach( page::get( 'contacts') as $c): ?>
      <dl class="contact-us" id="<?= slugify( $c->name ); ?>">
        <dt>Name</dt>
        <dd><?= $c->name ?></dd>
        <dt>Phone</dt>
        <dd><?= $c->phone ?></dd>
        <dt>Email</dt>
        <dd><?= $c->email ?></dd>
        <dt>Address</dt>
        <dd><?= $c->address->street ?>, PO Box <?= $c->address->pobox ?>, <?= $c->address->city ?> <?= $c->address->state ?> <?= $c->address->zip ?></dd>
        <dt>Website</dt>
        <dd><a href="<?= $c->website ?>"><?= $c->website ?></a></dd>
      </dl>
      <?php endforeach; ?>
    </div><!-- /column -->
    <div class='column last'>
    </div><!-- /column -->

  </div><!-- /twocolumns -->
</article>
