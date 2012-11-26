<h1><?= $form->getName() ?></h1>

<?php foreach($form as $field): ?>
<?php if($field->getName() == 'submit') continue; ?>
<p>
  <strong>
  <?= $field->getLabel() ?>
  [<?= $field->getName() ?>]
  </strong>
  <br>
  <?= $field->getValue() ?>
</p>
<?php endforeach ?>
