<?php
/* --------------------------------------------------
 * router.class.php
 * --------------------------------------------------
 */


// Direct access protection
if( !defined( 'LIRIOPE' )) die( 'Direct access is not allowed.' );

class router {

  /* --------------------------------------------------
   * getParts
   * --------------------------------------------------
   * Following the routing rules, returns the parts of the route
   *
   * http://site.com/var1/var2/var3/var4/var5/var6/...
   * RULES:
   * H) var1 is empty, assign var1=home, use Rule #2 
   * 1) var1 is a controller
   *      a) var2 is blank, use default
   *      b) var2 is an action in that controller
   * 2) var 1 is a folder in /web/content,
   *    use the default controller, filepage action
   *    which implements the FolderfileModel
   *      a) var2 is blank, use default
   *      b) var2 is a file within that folder
   *      c) var2 is a folder, proceed to (2a) to check var3
   */
  static function getParts() {
    $parts = uri::getURIArray();
#var_dump( $parts );
    
    // is the first part a controller?
    $controller = strtolower( $parts[0] );
    $controllerFile = ucwords( LiriopeTools::cleanInput( $controller, 'alphaOnly' )) . 'Controller.class.php';
    if( !load::seek( $controllerFile )) {
      if( empty( $parts[0] )) {
#echo("Rule #H<br>\n");
        $parts = NULL;
        page::set( 'homepage', TRUE );
      }
#echo("Rule #2<br>\n");
      // use Rule #2
      $controller = c::get( 'default.controller' );
      $action = 'filepage';
      $getVars = $parts;
    } else {
#echo("Rule #1<br>\n");
      array_shift( $parts );
      // we're following Rule #1
      $action = !empty( $parts[0]) ? array_shift( $parts ) : c::get( 'default.action' );
      $getVars = self::pairGetVars( $parts );
    }

    return array(
      'controller' => $controller,
      'action'     => $action,
      'getVars'    => $getVars
    );
  }

  /* --------------------------------------------------
   * pairGetVars
   * --------------------------------------------------
   * turns an array of values into a key=>value pair
   *
   */
  static function pairGetVars( $vars=array() ) {
    $array = array();
    while( !empty( $vars ) ) 
    {
      if( count( $vars ) >= 2 )
      {
        $key = array_shift( $vars );
        $value = array_shift( $vars );
        $array[ $key ] = $value;
        continue;
      }
      $value = array_shift( $vars );
      $array[] = $value;
    }
    return (array) $array;
  }

  /* --------------------------------------------------
   * destructURI (DEPRECATED)
   * --------------------------------------------------
   *
   */
  static function destructURI() {
    $routeArray = uri::getURIArray();

    /*
     * Controller
     * --------------------------------------------------
     * The first value in the array will be the name
     * of the page controller, but if it's empty, then
     * get the defulat controller name
     */
    $controller = !empty( $routeArray[0] ) ? $routeArray[0] : c::get( 'default.controller' );
    array_shift($routeArray);

    /*
     * Action
     * --------------------------------------------------
     * The next value in the array will be the action to
     * use within the controller.
     */
    $action = !empty( $routeArray[0] ) ? $routeArray[0] : c::get( 'default.action' );
    array_shift($routeArray);

    // Any other parts of the array are variable/value pairs. Parse them out.
    $getVars = self::pairGetVars( $routeArray );

    return array(
      'controller' => $controller,
      'action'     => $action,
      'getVars'    => $getVars,
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
    if( load::seek( $target )) {

      // Does the object exist?
      if( class_exists( $controller )) {

        // Does the object have that function?
        if( method_exists( $controller, $action )) {

          // Ok, run that object's function!
          $dispatch = new $controller( $model, $controllerName, $action );
          call_user_func( array( $dispatch,$action ), $getVars );

        } else {

          throw new Exception( "The view <b>$action</b> doesn't seem to exist in the controller <b>$controller</b>." );

        }
      } else {

        throw new Exception( "We can't find the class file <b>" . ucfirst($controller) . ".class.php</b>." );

      }
    } else {

      // OK, so the controller file doesn't exist, but don't freak out!
      // Perhaps there is a view sitting in the default folder. If there is
      // then just show that HTML.
      if( load::exists( c::get( 'default.controller' ) . "/$controllerName.php" )) {

        // Ok, run that hidden page!
        $dispatch = new LiriopeController( 'Liriope', 'default', $controllerName );
        call_user_func_array( array( $dispatch,'dummyPages' ), $getVars );

      } else {

        // TODO: route to the default controller error action
        /* Error Generation Code Here */
        header("HTTP/1.0 404 Not Found");
        // TODO: remove the error folder from .htaccess
        header("location: error/404.html");
        exit;

      }
    }
  }

} ?>
