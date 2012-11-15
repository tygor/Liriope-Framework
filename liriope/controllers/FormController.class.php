<?php
/**
 * FormController.class.php
 */

use Liriope\Component\Correspondence\Email;

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

    $mail = new Email();
    $mail->sendTo(array('tygor81@gmail.com'))->sendFrom('info@liriope.ubun');
    $mail->subject('Test email');
    $mail->message('This is a test message that has no meaning what-so-ever and it definately should be ignored if you happen to come across it in any form.');

var_dump($mail->send());
echo "\n";
print_r($mail);
echo "\n";
exit;
    if($mail->send() !== FALSE) {
echo "SUCCESS\n";
print_r($mail);
exit("\nExiting before the redirect");
      go(url('form/success'));
    }
    else {
echo "ERROR\n";
print_r($mail);
exit("\nExiting before the redirect");
      go(url('form/error'));
    }

  }

  public function success($vars=NULL) {
    $page = $this->getPage();
  }

}
