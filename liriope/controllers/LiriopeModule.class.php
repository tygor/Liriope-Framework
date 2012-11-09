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

  // menu()
  // creates a menu object with navigation from the menu.yml file
  //
  // @param  int    $depth how deep should the menu display (default = NULL (everything))
  // @param  string $from  what should the top item be? (default = home, 'auto' displays the decendants of the
  //                       current page, or level slug where from yaml file)
  public function menu( $params=array() ) {
    global $module;

    // prep an error variable for the view to bail
    $module->error = FALSE;

    // if the menu yaml is missing, bail.
    $module->error = ( !$menuFile = load::exists( 'menu.yaml', c::get( 'root.application' ))) ? TRUE : FALSE;

    $depth = a::get($params, 'depth');
    $from  = a::get($params, 'from');

    // extract params to the module
    foreach($params as $k=>$v ) { $module->$k = $v; }

    // if the menu yaml is missing, bail.
    if( !$menuFile = load::exists( 'menu.yaml', c::get( 'root.application' ))) {
      $module->error = TRUE;
    }

    // create the menu object
    $menu = new menu();
    $menu->loadFromYaml($menuFile);

    // return the menu from a specific point and it's decendants if $from is set
    if($from) {
      if(strtolower($from)==='auto') {
        $swap = $menu->getCurrent();
      }
      else { $swap = $menu->findDeep($from); }
      if(!$swap) {
        $module->error = TRUE;
      }
      $menu = $swap;
    }

    // now truncate the menu object to the desired $depth
    if($depth && is_object($menu)) {
      $menu->trim($depth);
      // an empty menu is no good, so if it has no children, send the parent
      if(!$menu->hasChildren()) $menu = $menu->getParent();
    }

    // set variables for the view file to use
    $module->menu = $menu;
  }

  // DEPRECATED
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
