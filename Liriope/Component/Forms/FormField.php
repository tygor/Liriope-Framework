<?php

namespace Liriope\Component\Forms;

class FormField {

  // @var string The name of the form field and also the ID
  private $name;
  // @var string The form field type
  private $type;
  // @var string The label that is used for the form input field
  private $label;
  // @var mixed The value of the form field
  private $value;
  // @var array The parameters for the form field [required, autofocus, placeholder]
  private $parameters = array();

  /**
   * CONSTRUCTOR
   */
  public function __construct($params=array()) {
    $this->name    = \a::get($params, 'name',    NULL);
    $this->type    = \a::get($params, 'type',    NULL);
    $this->label   = \a::get($params, 'label',   NULL);
    $this->value   = \a::get($params, 'value',   NULL);
    $this->options = \a::get($params, 'options', array());

    // consider all leftover variables to be parameters
    $parameters = array_diff_key($params, array_flip(array('name','type','label','value')));
    foreach($parameters as $k=>$v) {
      $this->parameters[$k] = $v;
    }
  }

  public function getName() {
    return $this->name;
  }

  public function getType() {
    return $this->type;
  }
  
  public function getLabel() {
    return $this->label ?: FALSE;
  }

  public function getValue() {
    return $this->value ?: FALSE;
  }

  public function getOptions() {
    return $this->options;
  }

  public function getParameter($name, $default=FALSE) {
    return isset($this->parameters[$name]) ? $this->parameters[$name] : $default;
  }

  public function isRequired() {
    return $this->getParameter('required');
  }

  public function getPlaceholder() {
    return $this->getParameter('placeholder');
  }

  public function getAutofocus() {
    return $this->getParameter('autofocus');
  }

  public function getHelp() {
    return $this->getParameter('help');
  }

  public function store($v) {
    // $v must be SANITIZED
    $this->value = filter_var($v, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_AMP);
  }

}
