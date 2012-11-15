<?php
/**
 * FormController.class.php
 */

// Direct access protection
if( !defined( 'LIRIOPE' )) die( 'Direct access is not allowed.' );

class FormController extends LiriopeController {

  public function show($vars=NULL) {
    go(url());
  }

  public function get($vars=NULL) {
    $page = $this->getPage();
    $page->theme = c::get('form.theme', c::get('root.theme'));

    $id = a::get($vars, 'id');
    $folder = c::get('form.folder');
    $file = load::exists($folder.DIRECTORY_SEPARATOR.$id, c::get('root.web'));

    $yml = new Yaml($file);
    $data = $yml->parse();

    $form = new Form(a::get($vars,'id'));
    if(isset($form->theme)) {
      $page->theme = $form->theme;
    }
    $page->set('form', $form);
  }

  public function submit($vars=NULL) {
    $form = new Form(a::get($vars,'id'));

    go(url('form/success'));
  }

  public function success($vars=NULL) {
    $page = $this->getPage();
    $page->theme = c::get('form.theme', c::get('root.theme'));
  }

}
