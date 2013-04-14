<div<?php if($field->isRequired()): ?> class="required"<?php endif ?>>

  <?php if($field->getLabel()): ?>
  <label><?= $field->getLabel() ?></label>
    <?php if($field->getHelp()): ?>
    <span><?= $field->getHelp() ?></span>
    <?php endif ?>
  <?php endif ?>

  <input type="text"
    <?php if($field->getName()): ?>name="<?= $field->getName() ?>"<?php endif ?>
    <?php if($field->getPlaceholder()): ?>placeholder="<?= $field->getPlaceholder() ?>"<?php endif ?>
    <?php if($field->isRequired()): ?>required<?php endif ?>
    <?php if($field->getAutofocus()): ?>autofocus<?php endif ?>
  >

</div>
