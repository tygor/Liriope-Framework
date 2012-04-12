<?php
/* --------------------------------------------------
 * router.class.php
 * --------------------------------------------------
 */

// Direct access protection
if( !defined( 'LIRIOPE' )) die( 'Direct access is not allowed.' );

class router {
  static $rules = array();
  static $extraVars = array();

  //
  // Router Rules
  // --------------------------------------------------
  // getRule( $id )
  // returns the requested rule or false if there is none
  //
  // setRule( $id, $translation )
  // accepts an ID and it's translation so that you can direct
  // blog/(:any) to the Liriope/Folderfile action
  //
  // Rules are read in the order that they were stored
  // so the rules that are set last can/will override previous
  // rules.
  // 
  static function getRule( $id=NULL ) {
    if( $id === NULL ) return self::$rules;
    if( isset( self::$rules[$id] )) return self::$rules[$id];
    return false;
  }

  static function setRule( $id=NULL, $trans=NULL, $extraVars=NULL ) {
    if( $id === NULL ) return false;
    if( !is_array( $id )) {
      self::$rules[$id] = $trans;
      return true;
    }
    foreach( $id as $i ) {
      self::setRule( $i[0], $i[1] );
    }
  }

  static function matchRule( $parts ) {
    foreach( self::getRule() as $rule => $trans ) {
      $rules = explode( "/", $rule );
      // compare each part to each rule part
      // continue through array if true, move on if false
      for( $i=0; $i < count( $rules ); $i++ ) {
        // if the parts don't match and a wildcard isn't present, move along
        if( $rules[$i] !== $parts[$i] && $rules[$i] !== "*" ) break;
        // if we're on a wildcard, store the corresponding value
        if( $rules[$i] == "*" ) $store[] = $parts[$i];
        // if this is the end of the rules part, return the translation
        if( $i == ( count( $rules ) - 1 )) {
          if( is_array( $store ) ) {
            // replace any $n variable with what is in $store
            echo "$trans";
            $store = $store + array( NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL ); 
            list( ${1}, ${2}, ${3}, ${4}, ${5}, ${6}, ${7}, ${8} ) = $store;
            eval( "\$trans = \"$trans\";" );
            echo " --> $trans<br>";
          }
          $trans = explode( "/", $trans );
          return $trans + $parts;
        }
      }
    }
  }

  //
  // getParts()
  // --------------------------------------------------
  // Following the routing rules, returns the parts of the route
  //
  static function getParts() {
    $parts = uri::getURIArray();
    
$test = self::matchRule( $parts );

echo("<pre>");
var_dump($test);
echo("</pre>");
exit;
    
    // is the first part a controller?
    $controller = strtolower( $parts[0] );
    $controllerFile = ucwords( tools::cleanInput( $controller, 'alphaOnly' )) . 'Controller.class.php';
    if( !load::seek( $controllerFile )) {
      if( empty( $parts[0] )) {
        $parts = NULL;
        page::set( 'homepage', TRUE );
      }
      // use Rule #2
      $controller = c::get( 'default.controller' );
      $action = 'filepage';
      $getVars = $parts;
    } else {
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

  //
  // pairGetVars()
  // --------------------------------------------------
  // turns an array of values into a key=>value pair
  //
  static function pairGetVars( $vars=array() ) {
    $array = array();
    while( !empty( $vars ) ) {
      if( count( $vars ) >= 2 ) {
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

  //
  // callHook()
  // --------------------------------------------------
  // Calls the user function
  //
  // $controller @string The name of the class controller to use
  // $action     @string The function to call inside of the controller
  // $getVars    @array  Any name/value pairs for the action to use
  //
  static function callHook( $controller=NULL, $action=NULL, $getVars=NULL ) {

    // Expect the naming conventions:
    // Controllers are uppercase on words (ex: Shovel)
    //   with "Controller" appended
    // Models are the plural of the controller (ex: Shovels)
    //   (yes, grammer can be horrible here)
    $controllerName = $controller;
    $controller = ucwords( tools::cleanInput( $controller, 'alphaOnly' ));
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

        /* Error Generation Code Here */
        self::go( '/', 404 );

      }
    }
  }

  //
  // go
  // --------------------------------------------------
  // the redirection function
  //
  static function go ( $url=FALSE, $code=FALSE ) {

    if( empty( $url )) $url = c::get( 'root.URL', '/' );
    switch( $code ) {
      case 301:
        header( 'HTTP/1.1 301 Moved Permanently' );
        break;
      case 302:
        header( 'HTTP/1.1 302 Found' );
        break;
      case 303:
        header( 'HTTP/1.1 303 See Other' );
        break;
      case 404:
        header( 'HTTP/1.0 404 Not Found' );
        $url = '/error/404';
        break;
    }
    header( 'Location:' . $url);
    exit();
  }

} ?>
