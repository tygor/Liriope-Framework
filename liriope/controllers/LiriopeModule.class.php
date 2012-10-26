<?php
//
// LiriopeModule.class.php
//

// Direct access protection
if( !defined( 'LIRIOPE' )) die( 'Direct access is not allowed.' );

class LiriopeModule {
  var $_controller;
  var $_action;
  var $_model;

  // construct()
  // Initiates the View layer and creates the $page object
  // all other controllers will add to this $page object and finally, it will be rendered
  //
  // @param  string  $model The model to use for the page
  // @param  string  $controller The controller that holds the action for this page's logic
  // @param  string  $action The method of that controller to call
  // @return self    Returns itself as an object so that other methods can be chained
  //
  function __construct($model, $controller, $action) {
    $this->_controller = strtolower( $controller );
    $this->_action = strtolower( $action );
    $this->_model =& $model;

    $file = load::exists( $controller . '/_' . $action . '.php' );
    if( !$file ) trigger_error( "We can't find that view file: $file", E_USER_ERROR );

    global $module;
    $module = new Page( $file );
  }

  public function pagination( $vars ) {
    global $module;
    $module->page = a::get($vars,'page');
  }

  public function menu( $params=array() ) {
    global $module;

    // prep an error variable for the view to bail
    $module->error = FALSE;

    // if the menu yaml is missing, bail.
    if( !$menuFile = load::exists( 'menu.yaml', c::get( 'root.application' ))) {
      $module->error = TRUE;
    }

    // extract params to the module
    foreach($params as $k=>$v ) { $module->$k = $v; }

    $menu = new menu();
    $menu->loadFromYaml($menuFile);

    // set variables for the view file to use
    $module->menu = $menu;
  }

  public function submenu( $params=array() ) {
    global $module;

    // extract params to the module
    foreach($params as $k=>$v ) { $module->$k = $v; }

    // prep an error variable for the view to bail
    $module->error = FALSE;

    // if the menu yaml is missing, bail.
    if( !$menuFile = load::exists( 'menu.yaml', c::get( 'root.application' ))) {
      $module->error = TRUE;
    }

    $menu = new menu();
    $menu->loadFromYaml($menuFile);
    $submenu = $menu->getCurrent();

    if(!$submenu) $module->error = TRUE;

    // set variables for the view file to use
    $module->menu = $submenu;
  }

  function setView( $name ) {
    global $module;
    $file = load::exists( $this->_controller . '/_' . $this->_action . '-' . $name . '.php' );
    if(!$file) return FALSE;
    $module->_theme = $file;
  }

  function __destruct() {
    global $module;

    $html = $module->render( TRUE, 'module' );
    echo trim( $html );

    return $html;
  }

}

?>
