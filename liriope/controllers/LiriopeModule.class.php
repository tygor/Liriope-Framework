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

  public function pagination( $page ) {
    global $module;
    $module->page = $page;
  }

  public function menu( $page ) {
    global $module;

    $module->error = FALSE;
    if( !$file = load::exists( 'menu.yaml', c::get( 'root.application' ))) $module->error = TRUE;

    $yaml = new Yaml( $file );
    $menu = new menu();
    foreach( $yaml->parse(TRUE) as $v ) {
      $menu->addChild( $v['label'], $v['url'] );
      if( isset( $v['children'] )) {
        $parent = $menu->find( $v['url'] );
        foreach( $v['children'] as $c ) {
          $parent->addChild( $c['label'], $c['url'] );
        }
      }
    }

    if( $page->root() !== 'home' && !$menu->findActive ) {
      $menu->findDeep( $page->root() )->setActive();
    }

    $module->menu = $menu;
    $module->page = $page;
  }

  function __destruct() {
    global $module;

    $html = $module->render( TRUE, 'module' );
    echo trim( $html );

    return $html;
  }

}

?>
