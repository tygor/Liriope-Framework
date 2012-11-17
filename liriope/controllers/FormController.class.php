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

    if($response !== FALSE) {
      go(url('form/success'));
    }
    else {
      go(url('form/error'));
    }

  }

  private function email($form, $action) {
    /**
     * CHECKS
     */

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

    /**
     * DRAFT EMAILS
     */

    $options = $action['options'];

    $from = $options['from'];
    $subject = $options['subject'];
    $message = $options['message'];

    $userMail = new Email();
    $userMail->sendTo($to)->sendFrom($from)->subject($subject)->message($message);

    $receiptMail = new Email();
    $receiptMail->sendTo($from)->sendFrom('no-reply@'.\uri::getDomain())->subject('Submission from \'' . $form->getName() .'\' form');

    // TODO: wrap this into a View model or some such. So, probably a View model v2.0
    $template = 'receiptEmail.html.php';
    $folder = __DIR__.'/../../Liriope/Resources/views/Forms';
    \content::start();
    include($folder.DIRECTORY_SEPARATOR.$template);
    $receipt = \content::end(TRUE);

    $receiptMail->message($receipt);

    if($userMail->send() && $receiptMail->send()) {
      return TRUE;
    }
    return FALSE;
  }

  public function success($vars=NULL) {
    $page = $this->getPage();
  }

}
