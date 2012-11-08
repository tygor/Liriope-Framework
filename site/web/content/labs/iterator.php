<?php

$page->title = "Iterator";
$page->date = "2012-11-08";

class myMenu implements RecursiveIterator, Countable {
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
        $this->_[] = new myMenu($v, $this);
      }
    }
    if($this->url===uri::get()) {
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
  public function getChildren() { return $this->_; }
  public function hasChildren() { return $this->current() !== NULL && $this->current() !== FALSE; }
  public function getParent() { return $this->parent; }
  public function hasParent() { return empty($this->parent) ? FALSE : TRUE; }
  public function dump() {
    // formats
    $link = '<a href="%s"%s>%s</a>';
    $classTag = ' class="%s"';
    $listItem = '<li%s>%s%s</li>';
    $list = '<ul%s>%s</ul>';

    // params
    $status = $this->active ? ' active ' : '';
    $status .= $this->current ? ' current ' : '';
    $status = sprintf($classTag, trim($status));
    $me = sprintf($link, url($this->url), $status, $this->label);
    $kids = '';
    $children = new ArrayIterator($this->getChildren());
    foreach($children as $child) $kids .= $child->dump();
    $liStatus = $this->hasChildren() ? sprintf($classTag, 'deeper') : '';
    $ulStatus = $this->hasParent() ? sprintf($classTag, 'children') : '';

    // return
    return sprintf($list, $ulStatus, sprintf($listItem, $liStatus, $me, $kids));
  }
}

$menuFile = load::exists( 'menu2.yaml', c::get( 'root.application' ));
$menuYaml = new Yaml($menuFile);
$menu = new myMenu($menuYaml->parse());

?>

<h1><?= $page->title ?></h1>

<nav>
  <?= $menu->dump() ?>
</nav>
