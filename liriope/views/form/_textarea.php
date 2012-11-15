<div<?php if($field['required']): ?> class="required"<?php endif ?>>
  <?php if(isset($field['label'])): ?>
  <label><?= $field['label'] ?>
    <?php if(isset($field['help'])): ?>
    <span><?= $field['help'] ?></span>
    <?php endif ?>
  </label>
  <?php endif ?>
  <textarea
    <?php if($field['name']): ?>name="<?= $field['name'] ?>"<?php endif ?>
    <?php if($field['placeholder']): ?>placeholder="<?= $field['placeholder'] ?>"<?php endif ?>
    <?php if($field['required']): ?>required<?php endif ?>
  ></textarea>
</div>
