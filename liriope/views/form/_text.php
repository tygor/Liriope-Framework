<div<?php if($field['required']): ?> class="required"<?php endif ?>>
  <?php if(isset($field['label'])): ?><label><?= $field['label'] ?></label><?php endif ?>
  <input type="text"
    <?php if(isset($field['name'])): ?>name="<?= $field['name'] ?>"<?php endif ?>
    <?php if(isset($field['placeholder'])): ?>placeholder="<?= $field['placeholder'] ?>"<?php endif ?>
    <?php if(isset($field['required'])): ?>required<?php endif ?>
    <?php if(isset($field['autofocus'])): ?>autofocus<?php endif ?>
  >
</div>
