<?php
// --------------------------------------------------
// router.class.php
// --------------------------------------------------

// Direct access protection
if( !defined( 'LIRIOPE' )) die( 'Direct access is not allowed.' );

class router {
  static $rules = array();
  static $name;
  static $rule;
  static $controller;
  static $action;
  static $params = array();

  //
  // getDispatch()
  // reads the router rules, compares to the URI parts
  // and returns the $controller, $action, and $params
  //
  static function getDispatch() {
    $request = uri::getArray();

    if( $request[0] === 'home' ) {
        self::$rule = self::getRule( 'home' );
    } elseif( !self::matchRule( $request )) {
      trigger_error( 'Fatal Liriope Error: No router rule was matched.', E_USER_ERROR );
    }
    self::useRoute( self::$rule->translate( $request ));

    // DEBUGGING
    if( c::get( 'debug' )) {
    trigger_error( "<b>Routing rule '".self::$name."'  matched.</b><br> Dispatching to <b>" .
      self::$controller . "</b>, <b>" . self::$action . "</b>()<br> " .
      "with the params: " . print_r( self::$params, TRUE ),
      E_USER_NOTICE );
    }

    return array(
      'controller' => self::$controller,
      'action'     => self::$action,
      'params'     => self::$params
    );
  }

  static function pairParams() {
    $params = self::$params;

    // allow for dirty routes by cleaning up null params
    foreach( $params as $k => $p ) if( empty( $p )) unset( $params[$k] );

    $params = array_chunk( $params, 2 );
    // convert params into key/value array
    $p = array(); $k = 0;
    foreach( $params as $pair ) {
      if( isset( $pair[1] )) $p[$pair[0]] = $pair[1];
      else $p[$k++] = $pair[0];
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
    self::$rules[$name] = new routerRule( $name, $rule, $route );
    return true;
  }

  // getRule()
  // returns a rule by $name, or the whole set if empty
  //
  // @param  string  $name The rule to return by name
  // @return string  The resulting value of that name
  // 
  static function getRule( $name=NULL ) {
    if( $name === NULL ) return array_reverse( self::$rules, TRUE );
    return a::get( self::$rules, $name, FALSE );
  }

  // rule()
  // returns the named rule in a string to be used by the url() function
  //
  // @param  string  $name The named rule to use
  // @return strgin  Returns the rule controller and action as a url string
  static function rule( $name=NULL ) {
    if( $name===NULL ) return '/'; 
    $rule = self::getRule( $name );
    return implode( '/', $rule->getConstants() );
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
    foreach( self::getRule() as $name => $parts ) {
      if( count( $request ) < $parts->countMinimum() ) continue;
      if( !$parts->matchConstants( $request )) continue;

      // RULE MATCHED
      self::$rule = $parts;
      return TRUE;
    }
    return FALSE;
  }

  // useRoute()
  // records the parts of the matched rule into the router object
  // this is the translation of the rule into usable dispatch
  //
  static function useRoute( $route ) {
    $parts = explode( '/', $route );
    self::$name       = self::$rule->name;
    self::$controller = array_shift( $parts );
    self::$action     = array_shift( $parts );
    //$parts = preg_replace( '/\$\d+/', '', $parts );
    self::$params     = $parts;
    self::pairParams();
    return true;
  }

  //
  // callHook()
  // --------------------------------------------------
  // Calls the user function
  //
  // $controller    @string The controller following the naming conventions
  // $controllerRaw @string The name of the class controller to use
  // $action        @string The function to call inside of the controller
  // $model         @string The name of the associated model
  // $getVars       @array  Any name/value pairs for the action to use
  //
  static function callHook( $controller, $controllerRaw, $action, $model, $getVars=array(), $return=FALSE ) {
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
    $dispatch = new $controller( $model, $controllerRaw, $action );
    $content = call_user_func( array( $dispatch,$action ), $getVars );
    if( $return ) return $content;
    return TRUE;
  }

  //
  // callController()
  // --------------------------------------------------
  // Calls the user function
  //
  // $controller @string The name of the class controller to use
  // $action     @string The function to call inside of the controller
  // $getVars    @array  Any name/value pairs for the action to use
  //
  static function callController( $controller=NULL, $action=NULL, $getVars=NULL ) {
    // Controllers are uppercase on words (ex: Shovel) with "Controller" appended
    // Models are the plural of the controller (ex: Shovels)
    $controllerRaw = $controller;
    $controller = ucwords( tools::cleanInput( $controller, 'alphaOnly' ));
    $model = rtrim( $controller, 's' );
    $controller .= 'Controller';
    self::callHook( $controller, $controllerRaw, $action, $model, $getVars );
  }

  //
  // callModule()
  // --------------------------------------------------
  // Calls the user function
  //
  // $controller @string The name of the class controller to use
  // $action     @string The function to call inside of the controller
  // $getVars    @array  Any name/value pairs for the action to use
  //
  static function callModule( $controller=NULL, $action=NULL, $getVars=NULL ) {
    // Controllers are uppercase on words (ex: Shovel) with "Module" appended
    // Models are the plural of the controller (ex: Shovels)
    $controllerRaw = $controller;
    $controller = ucwords( tools::cleanInput( $controller, 'alphaOnly' ));
    $model = rtrim( $controller, 's' );
    $controller .= 'Module';
    return self::callHook( $controller, $controllerRaw, $action, $model, $getVars, TRUE );
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

}

class routerRule {
  var $name;
  var $rule;
  var $route;
  var $_rule = array();
  var $_constant = array();
  var $_mandatory = array();
  var $_optional = array();

  function __construct( $name, $rule, $route ) {
    $this->name = $name;
    $this->readRule( $rule );
    $this->route = $route;
  }

  // readRule()
  // Reads the rule, breaks it appart, and sets rule info
  //
  function readRule( $rule ) {
    $this->rule = $rule;

    $rule = trim( $rule, '/' );
    $parts = explode( '/', $rule );
    foreach( $parts as $k => $part )  {
      $wildcard = substr( $part, 0, 1 );
      switch( $wildcard ) {
        case '!':
          $this->_mandatory[$k] = ltrim( $part, '!' );
          $this->_rule[$k] = ltrim( $part, '!' );
          break;
        case ':':
          $this->_optional[$k] = ltrim( $part, ':' );
          $this->_rule[$k] = ltrim( $part, ':' );
          break;
        case '*':
          break;
        default:
          $this->_constant[$k] = $part;
          $this->_rule[$k] = $part;
          break;
      }
    }
  }

  function countMandatory() {
    return count( $this->_mandatory );
  }

  function getMandatorys() {
    return (array) $this->_mandatory;
  }

  function getOptionals() {
    return (array) $this->_optional;
  }

  function countConstant() {
    return count( $this->_constant );
  }

  function getConstants() {
    return (array) $this->_constant;
  }

  function matchConstants( $request=array() ) {
    if( $this->countConstant() == 0 ) return TRUE;
    foreach( $this->getConstants() as $k => $c ) {
      if( $request[$k] !== $c ) return FALSE;
    }
    return TRUE;
  }

  function countMinimum() {
    return $this->countMandatory() + $this->countConstant();
  }

  // translate()
  // takes the request and returns the resulting URI array
  //
  // @param  array  $request The URI Array
  // @return array  The translated URI array
  //
  function translate( $request=array() ) {
    $slice = array_slice( $request, 0, $this->countMinimum() );
    $postRule = array_diff( $request, $slice );
    foreach( $this->getMandatorys() as $k => $v ) {
      if( isset( $request[$k] )) $$v = $request[$k];
      else $$v = '';
    }
    foreach( $this->getOptionals() as $k => $v ) {
      if( isset( $request[$k] )) $$v = $request[$k];
      else $$v = '';
    }
    eval( "\$return = \"$this->route\";" );
    $return = trim( $return, '/' ) . '/' . implode( '/', $postRule );
    return trim( $return, '/' );
  }

}

