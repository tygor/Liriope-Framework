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
    // A theme should be set in the configuration or default to
    // the theme packaged with Liriope
    $theme = c::get( 'theme' );
    $theme = empty( $theme ) ? c::get( 'theme.default' ) : c::get( 'theme' );
    $this->setTheme( $theme );
    
    // return this object for chaining functions
    return $this;
  }

  function setTheme( $theme ) {
    $themeName = ucfirst( $theme ) . 'Theme';
    $theme = new $themeName();
    
    // tell the theme if this is the homepage (uses the default controller and action)
    $defaultC = c::get( 'controller.default' );
    $defaultA = c::get( 'action.default' );
    if( $this->_controller  == $defaultC && $this->_action == $defaultA )
    {
      $theme->setHomePage( TRUE );
    }

    $this->_theme = $theme;
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
    if( isset( $this->_theme ) && is_object( $this->_theme ))
    {
      $content = $this->_page->render(FALSE);
      $this->_theme->save_content( $content );
      $this->_theme->render();
    }
    else
    {
      $this->_page->render();
    }
  }

}

