<?php

namespace Liriope\Component\Content;

use Liriope\Component\Content\Buffer;
use Liriope\Toolbox\String;
use Liriope\Toolbox\a;
use Liriope\Models\obj;

/**
 * This class represents a page object. It can be the whole page, or a building block of the page.
 */

class Page extends obj {

  // @var string The file to use for render
  private $_view;

  // @var string The title of the page
  public $title;

  // @var string The date for the page
  public $date;
  public $time;

  // @var string The name of the theme folder that wraps the page
  public $theme;

  // TODO: What's this $keywords for? Overloaded?
  var $keywords;

  // TODO: What's this $vars for? Is it overloaded params?
  var $vars = array();

  function __construct( $file=NULL ) {
    $this->_view = $file;
    $this->title = \c::get( 'page.title' );
    $this->description = \c::get( 'page.description' );
    $this->author = \c::get( 'page.author' );
    $this->theme = \c::get( 'theme' );
  }

  public function set( $key, $value=FALSE ) {
    if( is_array( $key )) {
      self::$vars = array_merge( self::$vars, $key );
    } elseif( $value === NULL ) {
      if(isset(self::$vars)) unset( self::$vars[$key] );
    } else {
      $this->vars[$key] = $value;
    }
  }

  public function get( $key=NULL, $default=NULL ) {
    if( $key===NULL ) return (array) $this->vars;
    if( isset( $this->vars[$key] )) return $this->vars[$key];
    return $default;
  }

  /**
   * Manages the page title, either setting or getting
   *
   * @param  string $title The title for the page
   *
   * @return string Will return the page title on set and get, or '' if no title is set
   */
  public function title($title=NULL) {
      if($title === NULL) return $this->title ?: '';
      $this->title = $title;
      return $this->title;
  }

  /**
   * Manages the page date, either setting or getting
   *
   * @param  string $date The date of the page. Intrepetation is open to pubdate, modify date, or just date
   *
   * @return string Will return the page date on set and get, or '' if no date is set
   */
  public function date($date=NULL) {
      if($date === NULL) return $this->date ?: '';
      $this->date = $date;
      $this->time = strtotime($date);
      return $this->date;
  }

  /**
   * Returns the time representation for the date parameter
   *
   * @return string The time representation of the date parameter
   */
  public function time() {
      return $this->time ?: NULL;
  }

  public function useView($file) {
    $this->_view = $file;
    return TRUE;
  }

  /**
   * Ask the page which theme it will be wearing for the night
   */
  public function getTheme() {
      return $this->theme;
  }

  /**
   * Wardrobe change!
   *
   * @param string $name The name of the theme folder to use
   */
  public function setTheme($name) {
      $this->theme = $name;
  }

  public function css( $file=NULL, $rel='stylesheet' ) {
    if( $file===NULL ) return false;
    $file = trim( $file, '/' );
    if( substr( $file, 0, 4 ) != 'http' ) $file = '/' . $file;
    $this->vars['css'][] = array( 'file' => $file, 'rel' => $rel );
    return $file;
  }

  public function js( $file=NULL, $type='text/javascript' ) {
    if( $file===NULL ) return false;
    $this->vars['js'][] = array( 'file' => $file, 'type' => $type );
  }

  public function script( $script=NULL ) {
    if( $script===NULL ) return false;
    $this->vars['script'][] = $script;
  }

  private function transferStored() {
    // loop through the $this->get() variables and overload them
    foreach( $this->get() as $k => $v ) {
      $this->$k = $v;
    }
  }

  public function uri() {
    return $this->uri;
  }

  public function url( $route=FALSE ) {
    if( $this->isHomePage()) return url();
    if( $route && $this->controller && $this->action ) return $this->controller . '/' . $this->action;
    return url($this->uri);
  }

  public function root() {
    // return the root URI item
    $parts = explode( '/', trim( $this->uri, '/' ));
    return $parts[0];
  }

  public function keywords() {
    if(empty($this->keywords)) {
      $s = new String(\c::get('page.keywords'));
      $s = $s->split(',');
      $this->keywords = a::glue($s, ',');
    }
    return $this->keywords;
  }

  public function add_keywords($s) {
    if(!is_array($s)) {
      $s = new String($s);
      $s = $s->split(',');
    }
    $this->keywords = $this->keywords() . ',' . a::glue($s, ',');
  }

  public function isHomePage() {
    return ( $this->uri === \c::get( 'home' )) ? TRUE : FALSE;
  }

  public function isActive( $uri=NULL ) {
    if( $uri === NULL ) return false;
    if( $this->uri === $uri ) return true;
    return false;
  }

  //
  // render()
  // attempts to render the page
  //
  // @param  bool   $return if TRUE, return the buffer string, if FALSE print it
  // @param  string $alias  the variable name to use in the _view file for $this
  // @return mixed  Returns the output buffer string, or echos it
  public function render( $return=TRUE, $alias='page' ) {
    Buffer::start();
    $this->transferStored();
    // TODO: This alias idea, is it necessary? Why not use $this in page?
    $$alias = $this;
    include( $this->_view );
    $render = Buffer::end( $return );
    if( $return ) return $render;
    echo $render;
  }
}

?>
