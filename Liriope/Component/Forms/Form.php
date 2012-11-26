<?php

namespace Liriope\Component\Forms;

use Liriope\Component\Forms\FormField;

/**
 * The Form class pulls together all pieces of the form system.
 */

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

  // @var array An array of fields for the form
  public $fields = array();

  // @var string The action to take on form submission
  private $action;

  /**
   * CONSTRUCTOR
   *
   * @param string $id The file name for the form YAML data
   * @param string $folder The relative path to the folder that holds the YAML file
   *
   * @return void
   */
  public function __construct( $id=NULL, $folder=NULL ) {
    if($id===NULL) return FALSE;

    $this->id = $id;
    $this->folder = $folder===NULL ? \c::get('form.folder') : $folder;
    $file = \load::exists($this->folder.DIRECTORY_SEPARATOR.$this->id, \c::get('root.web'));

    $this->load($file);
  }

  /**
   * TO STRING
   */
  public function __toString() {
    return $this->id;
  }

  /**
   * This function accepts the form's YAML file and loads the variables into this object instance
   *
   * @param string $file The relative path from the web root to the form's YAML file
   *
   * @return void
   */
  private function load($file) {
    $yml = new \Yaml($file);
    $data = $yml->parse();

    $this->name = \a::get($data, 'name', NULL);
    $this->theme = \a::get($data, 'theme', \c::get('theme', 'grass'));
    $this->eventdate = \a::get($data, 'eventdate', NULL);
    $this->intro = \a::get($data, 'intro', NULL);
    $this->storeFields(\a::get($data, 'fields', FALSE));
    $this->action = \a::get($data, 'action', NULL);
  }

  private function storeFields($fields) {
    if($fields===FALSE) {
      $this->fields = array();
      $this->error = TRUE;
      return FALSE;
    }

    foreach($fields as $k=>$v) {
      $this->fields[\a::get($v,'name',$k)] = new FormField($v);
    }
  }
  
  public function hasErrors() { return $this->error ? TRUE : FALSE; }

  public function getName() {
    return $this->name;
  }

  public function getField($name) {
    return isset($this->fields[$name]) ? $this->fields[$name] : NULL;
  }

  public function getAction() {
    return $this->action;
  }

  /**
   * This funciton takes the form submission and stores the values in the FormField object
   *
   * @param _POST $post The response from the form submission
   */
  public function store($post) {
    // $post must be SANITIZED
    foreach($post as $k=>$v) {
      $field = $this->getField($k);
      if($field) $field->store($v);
    }
  }

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
