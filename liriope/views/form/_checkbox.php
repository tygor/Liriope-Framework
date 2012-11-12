<div<?php if($field['required']): ?> class="required"<?php endif ?>>
  <?php if(isset($field['label'])): ?><label><?= $field['label'] ?></label><?php endif ?>
  <?php foreach($field['options'] as $option): ?>
  <input type="checkbox"
    <?php if($field['name']): ?>name="<?= $field['name'] ?>"<?php endif ?>
    <?php if($option['value']): ?>name="<?= $option['value'] ?>"<?php endif ?>
    <?php if(isset($option['checked']) && $option['checked']): ?>checked<?php endif ?>
  >
  <?php if($option['label']): ?><?= $option['label'] ?><?php endif ?>
  <?php endforeach ?>
</div>
