<?php
//
// Form.class.php
//

// Direct access protection
if( !defined( 'LIRIOPE' )) die( 'Direct access is not allowed.' );

class Form implements Iterator {
  // the id of the form and also the filename of the config for the form
  private $id;
  // the folder that contains the config files
  private $folder;

  private $error;

  public $name;
  public $theme;
  public $eventdate;
  public $intro;
  public $send_to = array();
  public $fields = array();
  public $submit;

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

  public function rewind() { reset($this->fields); }
  public function current() { return current($this->fields); }
  public function next() { return next($this->fields); }
  public function key() { return key($this->fields); }
  public function valid() { return ($this->key() !== NULL && $this->key() !== FALSE) ? TRUE : FALSE; }
}
?>
