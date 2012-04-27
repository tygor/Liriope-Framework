<?php
// --------------------------------------------------
// router.class.php
// --------------------------------------------------

// Direct access protection
if( !defined( 'LIRIOPE' )) die( 'Direct access is not allowed.' );

class router {
  static $rules = array();
  static $controller;
  static $action;
  static $params = array();

  //
  // getDispatch()
  // reads the router rules, compares to the URI parts
  // and returns the $controller, $action, and $params
  //
  static function getDispatch() {
    // redirects file URLs like: image.jpg or styles.css
    // TODO: My goal is to relay to direct files but capture the controller/action for content files. Sadly, content images are stored under the content folder (perhaps a problem) so what I'm truly doing is checking for specific extensions and allowing them by extension.
    $file = uri::param( 'file' );
    if( $file ) {
      trigger_error( "The URI file is (" . var_export( $file, TRUE ) . ")", E_USER_NOTICE );

      // TODO: check extension against accepted pass-through extensions then go() to them
      $url = c::get( 'url' ) . '/' . c::get( 'root.content', 'content' ) . '/' . implode( '/', $uri ) ;
      router::go( $url );
    }

    // check for a matched rule and direct into the MVC structure
    if( !self::matchRule( uri::getURIArray() )) trigger_error( 'Fatal Liriope Error: No router rule was matched.', E_USER_ERROR );

    trigger_error( "<b>Routing rule matched.</b> Dispatching to <b>" . self::$controller . "</b>, <b>" . self::$action . "</b>() with the params: " . print_r( self::$params, TRUE ), E_USER_NOTICE );

    return array(
      'controller' => self::$controller,
      'action'     => self::$action,
      'params'     => self::$params
    );
  }

  //
  // getRule()
  // returns a rule by $id, or the whole set if empty
  //
  // @param  string  $id The id to return
  // @return string  The resulting value of that id
  // 
  static function getRule( $id=NULL ) {
    if( $id === NULL ) return self::$rules;
    return a::get( self::$rules, $id, FALSE );
  }

  //
  // setRule()
  // stores a rule to use during dispatch
  //
  // @param  mixed  $id The URI part to translate, and also the unique ID
  // @param  mixed  $result The translation into controller/action?params
  // @return bool   TRUE on sucess, FALSE on error
  //
  static function setRule( $id=NULL, $result=NULL ) {
    if( empty( $id )) {
      trigger_error( 'setRule was passed an empty parameter', E_USER_NOTICE );
      return false;
    }
    if( !is_array( $id )) {
      self::$rules[$id] = $result;
      return true;
    }
    foreach( $id as $i ) {
      self::setRule( $i[0], $i[1] );
    }
  }

  // 
  // matchRule()
  // compares the $request to the router rules
  // and determines where to dispatch
  //
  // Rules are read in the order that they were stored
  // so the rules that are set last can/will override previous
  // rules.
  //
  // @param  array  $request The URI array
  // @return bool   TRUE on succes, FALSE on error
  //
  static function matchRule( $request ) {

    foreach( self::getRule() as $rule => $result ) {
      // turn the rules into an array
      $rules = explode( "/", $rule );

      // loop through the $rules parts to find a match
      for( $i=0; $i < count( $rules ); $i++ ) {
        
        // are we at the end of the $request?
        // then we match, and we fill in the request with the rule
        if( !isset( $request[$i] )) $request[$i] = $rules[$i];

        // if the parts don't match and thre is no wildcard fallback,
        // break the for loop to move on to the next rule
        if( $rules[$i] !== $request[$i] && $rules[$i] !== "*" ) break;

        // store the $part value if we're on a wildcard
        $store = array();
        if( $rules[$i] == "*" && isset( $request[$i] )) $store[] = $request[$i];

        if( $i == ( count( $rules ) - 1 )) {
          // RULE MATCHED
          $result = self::ruleReplace( $result, $store );

          // return it's translation
          $result = explode( "/", $result );
          self::$controller = array_shift( $result );
          self::$action     = array_shift( $result );
          self::$params     = $result + $request;
          return true;
        }
      }
    }

    // no rule matched
    return false;
  }

  static function ruleReplace( $subject, $source ) {
    // find all of the placeholders: \$\d+ (ex. $1)
    $pattern = '/\$\d+/';
    $count = preg_match_all( $pattern, $subject, $matches );

    // then replace them
    foreach( $matches[0] as $k => $match ) {
      $subject = str_replace( $match, $source[$k], $subject );
    }

    // return result
    return $subject;
  }

  //
  // getParts()
  // --------------------------------------------------
  // Following the routing rules, returns the parts of the route
  //
  static function getParts() {
    $parts = uri::getURIArray();
    
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
  // go()
  // redirects to a new location
  //
  // @param  string  $url The URL to redirect to
  // @param  int     $code The HTTP status code
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
