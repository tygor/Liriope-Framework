<?php
/**
 * LiriopeRouter.class.php
 */

# --------------------------------------------------
# Setup an Autoloader
# --------------------------------------------------
spl_autoload_register( function ( $className ) { 
    $possibilities = array( 
        SERVER_ROOT.DS.'application'.DS.'controllers'.DS.$className.'.class.php', 
        SERVER_ROOT.DS.'application'.DS.'models'     .DS.$className.'.class.php', 
        SERVER_ROOT.DS.'application'.DS.'views'      .DS.$className.'.class.php', 
        SERVER_ROOT.DS.'library'                     .DS.$className.'.class.php', 
        $className.'.class.php' 
    ); 
    try {
      $loaded = false;
      foreach( $possibilities as $file ) { 
          if( file_exists( $file )) { 
              require_once( $file ); 
              $loaded = true;
          } 
      } 
      if( !$loaded ) {
        throw new Exception( 'Unable to find the ' . $className . ' Object in the SERVER_ROOT' );
      }
    } catch( Exception $e ) {
        header("HTTP/1.0 500 Internal Server Error");
        echo $e->getMessage();
        exit;
    }
    return true; 
}); 

# --------------------------------------------------
# Begin working with the REQUEST_URI for routing
# --------------------------------------------------

// Main Call Function
$url = $_SERVER['REQUEST_URI'];
function callHook() {

  // This is what I'm expecting to see here:
  // http://somesite.com/controller-name/action-name/variable/value/variable/value

  // Get the REQUEST_URI
  $route = $_SERVER['REQUEST_URI'];

  // clean up leading and trailing slashes
  $route = trim( $route, '/' );

  // Parse the page request and other GET variables
  $routeArray = array();
  $routeArray = explode( '/', $route );
  
  // If index.php was used in the url, it will be the first
  // item in the array. We don't need it.
  if( strtolower( $routeArray[0] ) == 'index.php' ) { array_shift( $routeArray ); }

  // Controller
  // The first value in the array will be the name
  // of the page (controller), but if it's empty, then use
  // the homepage: default
  $controller = !empty( $routeArray[0] ) ? $routeArray[0] : 'default';
  array_shift($routeArray);

  // Action
  // The next value in the array will be the action to
  // use within the controller. default = 'show'
  $action = !empty( $routeArray[0] ) ? $routeArray[0] : 'show';
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

  // Expect the naming conventions:
  // Controller are uppercase on words (ex: Shovel)
  //   with "Controller" appended
  // Models are the plural of the controller (ex: Shovels)
  //   (yes, grammer can be horrible here)
	$controllerName = $controller;
	$controller = ucwords( LiriopeTools::cleanInput( $controller, 'alphaOnly' ));
	$model = rtrim( $controller, 's' );
	$controller .= 'Controller';

  // Build the path to the controller file
  $target = SERVER_ROOT . DS . 'application' . DS . 'controllers' . DS .
    $controller . '.class.php';

  // HERE'S THE MAGIC
  // Grab that file
  if( file_exists( $target ))
  {
    include_once( $target );

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
        /* Error Generation Code Here */
        die( 'View does not exist!' );
      }
    }
    else
    {
      /* Error Generation Code Here */
      die( 'Class does not exist!' );
    }
  }
  else
  {
    // OK, so the controller file doesn't exist, but don't freak out!
    // Perhaps there is a view sitting in the default folder. If there is
    // then just show that HTML.
    $target = SERVER_ROOT . DS . 'application' . DS . 'views' . DS . 'default' . DS . "$controllerName.php";
    if( file_exists( $target ))
    {
      // Ok, run that hidden page!
      $dispatch = new LiriopeController( 'Liriope', 'default', $controllerName );
      call_user_func_array( array( $dispatch,'dummyPages' ), $getVars );
    }

    // TODO: route to the home page with an error
    // or show the 404 page.
    /* Error Generation Code Here */
    header("HTTP/1.0 404 Not Found");
    exit;
  }
}

