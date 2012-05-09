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
    // pass-through file URLs like: image.jpg or styles.css
    $file = uri::param( 'file' );
    if( $file ) {
      trigger_error( "The URI file is (" . var_export( $file, TRUE ) . ")", E_USER_NOTICE );

      // TODO: check extension against accepted pass-through extensions then go() to them
      $url = c::get( 'url' ) . '/' .
        c::get( 'root.content', 'content' ) . '/' .
        implode( '/', uri::get());
      router::go( $url );
    }

    // check for a matched rule and direct into the MVC structure
    if( !self::matchRule( uri::getURIArray() )) trigger_error( 'Fatal Liriope Error: No router rule was matched.', E_USER_ERROR );

    // DEBUGGING
    trigger_error( "<b>Routing rule matched.</b><br> Dispatching to <b>" .
      self::$controller . "</b>, <b>" . self::$action . "</b>()<br> " .
      "with the params: " . print_r( self::$params, TRUE ),
      E_USER_NOTICE );

    self::makeParams();

    return array(
      'controller' => self::$controller,
      'action'     => self::$action,
      'params'     => self::$params
    );
  }

  static function makeParams() {
    $params = self::$params;

    // convert params into key/value array
    $p = array();
    for( $i = 0; $i < count( $params); $i++ ) {
      if( isset( $params[$i+1] )) $p[$params[$i]] = $params[++$i];
      else $p[] = $params[$i];
    }
    self::$params = $p;
  }

  // setRule()
  // stores a rule to use during dispatch
  //
  // @param  string $name The name for the routing rule
  // @param  string $rule The rule to match to the request URI
  // @param  string $route The translation into controller/action?params
  // @return bool   TRUE on sucess, FALSE on error
  //
  static function setRule( $name=NULL, $rule=NULL, $route=NULL ) {
    if( $name === NULL || $rule === NULL || $route === NULL ) {
      trigger_error( 'setRule was passed an empty parameter', E_USER_NOTICE );
      return false;
    }
    self::$rules[$name] = array( 'rule'=>$rule, 'route'=>$route );
    return true;
  }

  // getRule()
  // returns a rule by $name, or the whole set if empty
  //
  // @param  string  $name The rule to return by name
  // @return string  The resulting value of that name
  // 
  static function getRule( $name=NULL ) {
    if( $name === NULL ) return self::$rules;
    return a::get( self::$rules, $name, FALSE );
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
    // if $request is empty... call the homepage
    if( empty( $request[0] )) {
      return self::useRoute( self::getRule( 'home' ));
    }

    foreach( self::getRule() as $name => $parts ) {

      // turn the rule into an array
      $rule = trim( $parts['rule'], '/' );
      $rule = explode( '/', $rule );

      $wildcards = array();
      $match = $request;

      // loop through the $rule parts to find a match
      for( $i=0; $i < count( $rule ); $i++ ) {
        
        // NO MATCH LOGIC
        // --------------------------------------------------
        // if request[i] is empty, the rule is longer. NO MATCH!
        if( !isset( $request[$i] )) {
          break;
        }
        // if rule[i] and request[i] don't match. NO MATCH!
        if( $rule[$i] !== '*' && $rule[$i] !== $request[$i] ) {
          break;
        }
        // if this is the last rule part, is not a wildcard,
        // and the request continues. NO MATCH!
        if( $i == ( count( $rule ) - 1 )
            && isset( $request[$i + 1] )
            && $rule[$i] !== '*' ) {
          break;
        }

        // WILDCARD HANDLING
        // --------------------------------------------------
        // if the wildcard is used, store it to replace with
        if( $rule[$i] == '*' && isset( $request[$i] )) $wildcards[] = $request[$i];

        // PARAMS TRACKER
        // --------------------------------------------------
        // cut the match off of the request clone so that we have the remainder when we match
        array_shift( $match );

        // MATCH LOGIC
        // --------------------------------------------------
        // if we get to this point, then we pass all no match logic
        // use this route and return to break the loops
        if( $i == ( count( $rule ) - 1 )) {
          // RULE MATCHED
          $params = array_merge( (array) $wildcards , (array) $match );
          return self::useRoute( self::getRule( $name ), $params );
        }
      }
    }

    // no rule matched
    return false;
  }

  static function useRoute( $rule, $match=array() ) {
    $parts = explode( '/', $rule['route'] );
    self::$controller = array_shift( $parts );
    self::$action     = array_shift( $parts );
    self::$params     = $match;
    return true;
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
    if( !load::seek( $target )) router::go( '/', 404 ); 

    // check that the class was loaded and that it has the correct method
    if( !class_exists( $controller )) trigger_error( "We can't find the class file <b>" . ucfirst($controller) . ".class.php</b>.", E_USER_ERROR );
    if( !method_exists( $controller, $action )) trigger_error( "The view <b>$action</b> doesn't seem to exist in the controller <b>$controller</b>.", E_USER_ERROR );

    // Ok, run that object's function!
    $dispatch = new $controller( $model, $controllerName, $action );
    call_user_func( array( $dispatch,$action ), $getVars );
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
