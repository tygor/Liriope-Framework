<div<?php if($field['required']): ?> class="required"<?php endif ?>>
  <?php if(isset($field['label'])): ?>
  <label><?= $field['label'] ?>
    <?php if(isset($field['help'])): ?>
    <span><?= $field['help'] ?></span>
    <?php endif ?>
  </label>
  <?php endif ?>
  <?php if(isset($field['options'])): ?>
  <p>
  <?php foreach($field['options'] as $c=>$option): ?>
  <?php if($c!==0): ?><br><?php endif ?>
  <input type="checkbox"
    <?php if($field['name']): ?>name="<?= $field['name'] ?>"<?php endif ?>
    <?php if($option['value']): ?>name="<?= $option['value'] ?>"<?php endif ?>
    <?php if(isset($option['checked']) && $option['checked']): ?>checked<?php endif ?>
  >
  <?php if($option['label']): ?><?= $option['label'] ?><?php endif ?>
  <?php endforeach ?>
  </p>
  <?php endif ?>
</div>
