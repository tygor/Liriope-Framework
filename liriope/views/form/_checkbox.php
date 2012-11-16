<div<?php if($field->isRequired()): ?> class="required"<?php endif ?>>

  <?php if($field->getLabel()): ?>
  <label><?= $field->getLabel() ?></label>
    <?php if($field->getHelp()): ?>
    <span><?= $field->getHelp() ?></span>
    <?php endif ?>
  <?php endif ?>

  <?php if($field->getOptions()): ?>
  <p>
  <?php foreach($field->getOptions() as $c=>$option): ?>
  <?php if($c!==0): ?><br><?php endif ?>
  <input type="checkbox"
    <?php if($field->getName()): ?>name="<?= $field->getName() ?>"<?php endif ?>
    <?php if($option['value']): ?>value="<?= $option['value'] ?>"<?php endif ?>
    <?php if(isset($option['checked']) && $option['checked']): ?>checked<?php endif ?>
  >
  <?php if($option['label']): ?><?= $option['label'] ?><?php endif ?>
  <?php endforeach ?>
  </p>
  <?php endif ?>

</div>
