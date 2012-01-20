<div id="<?= slugify( $c->name ); ?>">
  <p><?= $c->name ?><br/>
  <?= $c->phone ?><br/>
  <?= $c->email ?><br/>
  <?= $c->address->street ?>, PO Box <?= $c->address->pobox ?>, <?= $c->address->city ?> <?= $c->address->state ?> <?= $c->address->zip ?></p>
</div>
