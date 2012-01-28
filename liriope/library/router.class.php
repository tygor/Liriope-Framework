<?php

// Direct access protection
if( !defined( 'LIRIOPE' )) die( 'Direct access is not allowed.' );

/*
 * --------------------------------------------------
 * Router code
 * --------------------------------------------------
 */

class router
{

  static function destructURI() {
    // TODO: Be ready to account for a no Rewrite situation
    // which I may have already done, but I'm not sure
    
    /*
     * Expected URI string breakdown:
     * http://somesite.com/controller-name/action-name/variable/value/variable/value
     * http://somesite.com/index.php/controller-name/action-name/variable/value/variable/value
     */

    $route = $_SERVER['REQUEST_URI'];

    // clean up leading and trailing slashes
    $route = trim( $route, '/' );

    // Parse the page request and other GET variables
    $routeArray = array();
    $routeArray = explode( '/', $route );
    
    // If index.php was used in the url, it will be the first
    // item in the array. We don't need it so remove it
    if( strtolower( $routeArray[0] ) == 'index.php' ) { array_shift( $routeArray ); }

    /*
     * Controller
     * --------------------------------------------------
     * The first value in the array will be the name
     * of the page controller, but if it's empty, then
     * get the defulat controller name
     */
    $controller = !empty( $routeArray[0] ) ? $routeArray[0] : c::get( 'controller.default' );
    array_shift($routeArray);

    /*
     * Action
     * --------------------------------------------------
     * The next value in the array will be the action to
     * use within the controller.
     */
    $action = !empty( $routeArray[0] ) ? $routeArray[0] : c::get( 'action.default' );
    array_shift($routeArray);

    // Any other parts of the array are variable/value pairs. Parse them out.
    $getVars = array();
    while( !empty( $routeArray ) ) 
    {
      if( count( $routeArray ) >= 2 )
      {
        $key = array_shift( $routeArray );
        $value = array_shift( $routeArray );
        $getVars[ $key ] = $value;
        continue;
      }
      $value = array_shift( $routeArray );
      $getVars[] = $value;
    }

    return array(
      'controller' => $controller,
      'action'     => $action,
      'getVars'    => $getVars
    );
  }

  /**
   * callHook()
   * 
   * Calls the user function
   *
   * $controller @string The name of the class controller to use
   * $action     @string The function to call inside of the controller
   * $getVars    @array  Any name/value pairs for the action to use
   */
  static function callHook( $controller=NULL, $action=NULL, $getVars=NULL ) {

    // Expect the naming conventions:
    // Controller are uppercase on words (ex: Shovel)
    //   with "Controller" appended
    // Models are the plural of the controller (ex: Shovels)
    //   (yes, grammer can be horrible here)
    $controllerName = $controller;
    $controller = ucwords( LiriopeTools::cleanInput( $controller, 'alphaOnly' ));
    $model = rtrim( $controller, 's' );
    $controller .= 'Controller';

    // $getVars nees to be an array
    if( empty( $getVars )) $getVars = array();

    $target = $controller . '.class.php';

    // HERE'S THE MAGIC
    // Grab that file
    if( load::seek( $target ))
    {
      // Does the object exist?
      if( class_exists( $controller ))
      {
        // Does the object have that function?
        if( method_exists( $controller, $action ))
        {
          // Ok, run that object's function!
          $dispatch = new $controller( $model, $controllerName, $action );
          call_user_func_array( array( $dispatch,$action ), $getVars );
        }
        else
        {
          throw new Exception( "The view <b>$action</b> doesn't seem to exist in the controller <b>$controller</b>." );
        }
      }
      else
      {
        throw new Exception( "We can't find the class file <b>" . ucfirst($controller) . ".class.php</b>." );
      }
    }
    else
    {
      // OK, so the controller file doesn't exist, but don't freak out!
      // Perhaps there is a view sitting in the default folder. If there is
      // then just show that HTML.
      if( load::exists( c::get( 'controller.default' ) . "/$controllerName.php" ))
      {
        // Ok, run that hidden page!
        $dispatch = new LiriopeController( 'Liriope', 'default', $controllerName );
        call_user_func_array( array( $dispatch,'dummyPages' ), $getVars );
      }
      else
      {
        // TODO: route to the home page with an error
        // or show the 404 page.
        /* Error Generation Code Here */
        header("HTTP/1.0 404 Not Found");
        exit;
      }
    }
  }

}

?>
