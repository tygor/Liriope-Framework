<?php snippet('default/header'); ?>

<h1>Contact us</h1>

<div class='twocolumns'>

  <div class='column'>
    <dl class="contact-us">
      <dt>Phone</dt>
      <dd><?= $phone ?></dd>
      <dt>Email</dt>
      <dd><?= $email ?></dd>
      <dt>Address</dt>
      <dd><?= $street ?>, <?= $postofficebox ?>, <?= $city ?> <?= $state ?> <?= $zip ?></dd>
    </dl>
  </div><!-- /column -->
  <div class='column last'>
    <?php snippet( 'contact-us/contact_form' ); ?>
  </div><!-- /column -->

</div><!-- /twocolumns -->

<?php snippet('default/footer'); ?>
