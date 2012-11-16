<?php
/**
 * FormController.class.php
 */

use Liriope\Component\Forms\Form;
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
    $form->store($_POST);

    $action = $form->getAction();
    $use = $action['use'];
    $response = $this->$use($form, $action);

var_dump($response);exit;

    if($response !== FALSE) {
      go(url('form/success'));
    }
    else {
      go(url('form/error'));
    }

  }

  private function email($form, $action) {
    // to continue, the "to" value must be a valid email address
    $to = $form->getField('email')->getValue();
    
    // check for a valid email format
    if(!filter_var($to, FILTER_VALIDATE_EMAIL)) {
      throw new Exception('The email address entered into the form is not valid.');
    }

    // now check the domain name on the email
    list($user,$domain) = preg_split('/@/', $to, 2);
    if(!checkdnsrr($domain, 'MX') && !checkdnsrr($domain, 'A')) {
      throw new Exception('The email address entered into the form has an invalid domain.');
    }

    $options = $action['options'];

    $from = $options['from'];
    $subject = $options['subject'];
    $message = $options['message'];

    $mail = new Email();
    $mail->sendTo($to)->sendFrom($from)->subject($subject)->message($message);

    return $mail->send();
  }

  public function success($vars=NULL) {
    $page = $this->getPage();
  }

}
