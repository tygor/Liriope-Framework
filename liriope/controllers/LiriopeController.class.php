<?php
/**
 * LiriopeController.class.php
 */

// Direct access protection
if( !defined( 'LIRIOPE' )) die( 'Direct access is not allowed.' );

class LiriopeController {

  protected $_model;
  protected $_controller;
  protected $_action;
  protected $_theme;
  protected $_page;

  function __construct($model, $controller, $action) {

    $this->_controller = $controller;
    $this->_action = $action;
    $this->_model = $model;
    
# TODO: I'm not sure why I define model again here.
# Should it be removed?
    $this->$model =& $model;

    $page = new LiriopeView($controller,$action);
    $this->_page =& $page;

    // Theme
    // A theme should be set in the configuration else default to
    // the theme packaged with Liriope
    $theme = c::get( 'theme' );
    if( !$theme ) $theme = c::get( 'theme.default' );
    $this->setTheme( $theme );
    
    // return this object for chaining functions
    return $this;
  }

  function setTheme( $theme ) {
    $themeName = ucfirst( $theme ) . 'Theme';
    $this->_theme = $themeName;
    $themeName::start();
    if( $this->isHomepage() ) $themeName::set( 'body.class', 'homepage' );
  }

  function isHomepage() {
    if( $this->_controller == c::get( 'controller.default' ) &&
      $this->_action == c::get( 'action.default' )) {
      return true;
    } else {
      return false;
    }
  }

  function set($name,$value) {
    $this->_page->set($name,$value);
  }

  /**
   * For controller-less pages
   */
  public function dummyPages( $getVars=NULL )
  {
  }

  function __destruct() {
    $theme = $this->_theme;
    // if a theme is set, use it
    if( isset( $theme )) {
      $content = $this->_page->render(FALSE);
      $theme::save_content( $content );
      $theme::render();
    } else {
      $this->_page->render();
    }
  }

}

