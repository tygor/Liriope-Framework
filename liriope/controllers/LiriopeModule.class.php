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

  function __destruct() {
    global $module;

    $html = $module->render( TRUE, 'module' );
    echo trim( $html );

    return $html;
  }

}

?>
