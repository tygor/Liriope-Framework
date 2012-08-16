<?php if( !$module->error ): ?>
<?php foreach($module->tweets as $tweet): ?>
<p>@<?= $tweet['content'] ?></p>
<?php endforeach ?>
<?php else: ?>
<p class="label error"><?= $module->error ?></p>
<?php endif ?>
