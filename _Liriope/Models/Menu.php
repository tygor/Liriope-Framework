<?php

namespace Liriope\Models;

use Liriope\Toolbox\Uri;

// Direct access protection
if( !defined( 'LIRIOPE' )) die( 'Direct access is not allowed.' );

class menu implements \RecursiveIterator, \Countable {
  // the text displayed for the menu item
  private $label;
  // the relative url
  private $url;
  // the parent object
  private $parent;
  // active: this menu item is current or a predecessor of current
  private $active = FALSE;
  // current: this is the menu item currently displayed
  private $current = FALSE;
  // this array holds menu children
  public $_ = array();

  public function __construct($array, $parent=NULL) {
    $this->parent = $parent!==NULL ? $parent : NULL;
    $this->label = isset($array['label']) ? $array['label'] : NULL;
    $this->url   = isset($array['url'])   ? $array['url']   : NULL;
    if(isset($array['children'])) {
      foreach($array['children'] as $v) {
        $this->_[] = new menu($v, $this);
      }
    }
    if($this->url===Uri::get()) {
      $this->active = TRUE;
      $this->current = TRUE;
      if($this->hasParent()) $this->getParent()->setStatus(TRUE);
    }
  }
  private function setStatus($active=FALSE) {
    if($active) {
      $this->active = TRUE;
      if($parent = $this->getParent()) $parent->setStatus(TRUE);
    }
  }
  public function __toString() { return $this->label . " [" . $this->url . "] "; }
  public function current() { return current($this->_); }
  public function key() { return key($this->_); }
  public function next() { return next($this->_); }
  public function rewind() { reset($this->_); }
  public function valid() { return ($this->key() !== NULL & $this->key() !== FALSE) ? TRUE : FALSE; }
  public function count() { return count($this->_); }
  public function hasChildren() { return $this->current() !== NULL && $this->current() !== FALSE; }
  public function getChildren() { return $this->_; }
  public function hasParent() { return empty($this->parent) ? FALSE : TRUE; }
  public function getParent() { return $this->parent; }
  public function getLabel() { return $this->label; }
  public function getURL() { return $this->url; }
  public function isActive() { return $this->active; }
  public function isCurrent() { return $this->current; }
  public function findCurrent() {
    if($this->isCurrent()) return $this;
    $it = new \ArrayIterator($this->getChildren());
    foreach($it as $child) { if($found = $child->findCurrent()) return $found; }
    return FALSE;
  }
  public function findURL($url) {
    if($this->url === $url) { return $this; }
    $it = new ArrayIterator($this->getChildren());
    foreach($it as $child) { if($found = $child->findURL($url)) return $found; }
    return FALSE;
  }
}

?>
