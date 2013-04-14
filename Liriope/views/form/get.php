<hgroup>
  <h1><?= isset($page->form->name) ? $page->form->name : 'Untitled' ?></h1>
  <?php if(isset($page->form->eventdate)): ?>
  <p class="drophead"><?= date(c::get('date.format'), strtotime($page->form->eventdate)) ?></p>
  <?php endif ?>
  <?php if(isset($page->form->details)): ?>
  <p class="byline"><?= $page->form->details ?></p>
  <?php endif ?>
</hgroup>

<?php if(!$page->form->hasErrors()): ?>
 
  <?php if(isset($page->form->intro)): ?>
  <p><?= $page->form->intro ?></p>
  <?php endif ?>

  <form method="post" action="<?= url('form/submit/id/'.$page->form) ?>">
    <?php foreach($page->form as $field): ?>
      <?php partial('form',$field->getType(), array('field'=>$field)); ?>
    <?php endforeach ?>
  </form>

<?php else: ?>
  <p>This form is not configured proeperly</p>
<?php endif ?>
