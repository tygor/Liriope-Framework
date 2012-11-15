<?php
//
// Form.class.php
//

// Direct access protection
if( !defined( 'LIRIOPE' )) die( 'Direct access is not allowed.' );

class Form implements \Iterator {
  // @var boolean
  private $error = FALSE;

  // @var string The name of the form and the form heading
  public $name;

  // @var string The theme to use during display
  public $theme;

  // @var date (optional) The date of the event the form describes
  public $eventdate;

  // @var string (optional) Paragraph text to precede the form fields
  public $intro;

  // @var array An array of email addresses to send the form data to
  public $send_to = array();

  // @var email_address (optional) The email address for the "from" field of the confirmation email
  public $send_from = array();

  // @var array An array of fields for the form
  public $fields = array();

  public function __construct( $id=NULL, $folder=NULL ) {
    if($id===NULL) return FALSE;
    $this->id = $id;
    $this->folder = $folder===NULL ? c::get('form.folder') : $folder;
    $file = load::exists($this->folder.DIRECTORY_SEPARATOR.$this->id, c::get('root.web'));
    $this->load($file);
  }

  private function load($file) {
    $yml = new Yaml($file);
    $data = $yml->parse();
    $this->name = a::get($data,'name',NULL);
    $this->theme = a::get($data,'theme',NULL);
    $this->eventdate = a::get($data,'eventdate',NULL);
    $this->details = a::get($data,'details',NULL);
    $this->intro = a::get($data,'intro',NULL);
    $this->send_to = a::get($data,'send_to',array());
    $this->fields = a::get($data,'fields',FALSE);
    if($this->fields===FALSE) { $this->fields = array(); $this->error = TRUE; }
    $this->submit = a::get($data,'submit',NULL);
  }
  
  public function __toString() {
    return $this->id;
  }

  public function hasErrors() { return $this->error ? TRUE : FALSE; }

  /**
   * Iterator interface methods
   */
  public function rewind() { reset($this->fields); }
  public function current() { return current($this->fields); }
  public function next() { return next($this->fields); }
  public function key() { return key($this->fields); }
  public function valid() { return ($this->key() !== NULL && $this->key() !== FALSE) ? TRUE : FALSE; }
}
?>
