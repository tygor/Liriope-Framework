<?php
/**
 * LiriopeController.class.php
 */
useHelper( 'default' );

class LiriopeController {

  protected $_model;
  protected $_controller;
  protected $_action;
  protected $_theme;
  protected $_page;

  function __construct($model, $controller, $action, $theme=FALSE) {

    $this->_controller = $controller;
    $this->_action = $action;
    $this->_model = $model;
    
# TODO: I'm not sure why I define model again here.
# Should it be removed?
    $this->$model =& $model;

    $page = new LiriopeView($controller,$action);
    $this->_page =& $page;

    // if $theme is set, then put that in play
    if( !empty( $theme )) 
    {
      $this->setTheme( $theme );
    }
    
    // return this object for chaining functions
    return $this;
  }

# TODO: UNFINISHED THEME SYSTEM
# was a great idea I had while sleeping but I can't remember
# the whole process of getting it to work.
  function setTheme( $theme ) {
# TODO: Once a config system is in place, grab the below
# settings from the config and let this be an override.
    $themeName = 'Liriope';

    $theme = new LiriopeView( $themeName, 'showTheme' );
    $this->_theme = $theme;
    $this->_theme->set( '_page', $this->_page );
  }

  function set($name,$value) {
    $this->_page->set($name,$value);
  }

  function __destruct() {
    $this->_page->render();
  }

  /**
   * For controller-less pages
   */
  public function dummyPages( $getVars=NULL )
  {
  }

}

