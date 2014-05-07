<?php

namespace Liriope\Toolbox;

use Liriope\c;
use Liriope\Controllers;
use Liriope\Component\Load;
use Liriope\Component\Search\Index;

class Router {

  // an array of the rules set during configuration grab
  static $rules = array();

  static $name;
  static $rule;
  static $controller;
  static $use;
  static $action;
  static $params = array();

  //
  // getDispatch()
  // reads the router rules, compares to the URI parts
  // and returns the $controller, $action, and $params
  //
  // @param array $request The requested URI converted to an array on the '/'
  //
  static function getDispatch( $request=NULL ) {
    if( $request===NULL ) $request = Uri::getArray();
    $rule = ($request[0]==='home') ? self::getRule( 'home' ) : self::matchRule( $request );
    if( !$rule ) trigger_error( 'Fatal Liriope Error: No router rule was matched.', E_USER_ERROR );
    self::$use = $rule->getUse();
    // if the rule is a closure, simply return the function
    if(is_callable($rule->route)) return $rule->route;
    return( self::useRoute( $rule->translate( $request )));
  }

  // 
  // pairParams()
  // takes an array of values and, in order, pairs them as key/value
  // if there is an odd-one-out, it's key is numeric
  //
  static function pairParams( $params=NULL ) {
    // allow for dirty routes by cleaning up null params
    foreach( $params as $k => $p ) if( empty( $p )) unset( $params[$k] );

    $params = array_chunk( $params, 2 );
    // convert params into key/value array
    $p = array(); $k = 0;
    foreach( $params as $pair ) {
      if( isset( $pair[1] )) $p[$pair[0]] = $pair[1];
      else $p[$k++] = $pair[0];
    }
    return $p;
  }

  // setRule()
  // stores a rule to use during dispatch
  //
  // @param  string $name The name for the routing rule
  // @param  string $rule The pattern to match to the request URI
  // @param  string $route The translation into controller/action?params
  // @param  string $use  Switch to use either the controller or the module logic
  // @return bool   TRUE on success, FALSE on error
  //
  static function setRule( $name=NULL, $rule=NULL, $route=NULL, $use='controller' ) {
    if( $name === NULL || $rule === NULL || $route === NULL ) {
      trigger_error( 'setRule was passed an empty parameter', E_USER_NOTICE );
      return false;
    }
    $use = strtolower($use);
    self::$rules[$name] = new routerRule( $name, $rule, $route, $use );
    return true;
  }

  // getRule()
  // returns a rule by $name, or the whole set if empty
  //
  // @param  string  $name The rule to return by name
  // @return object  \Liriope\Toolbox\routerRule  The resulting object of that name
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
      return $parts;
    }
    return FALSE;
  }

  // useRoute()
  // returns the call_user_func parts of the route
  //
  static function useRoute( $route ) {
    $parts = explode( '/', $route );
    $controller = array_shift( $parts );
    $action =     array_shift( $parts );
    $params =     self::pairParams( $parts );
    $use =        self::$use;
    return array( 'controller'=>$controller, 'action'=>$action, 'params'=>$params, 'use'=>$use );
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

    $target = $controller . '.php';

    if( !Load::seek( $target )) {
      trigger_error( "calling the 404 b/c we can\'t find <b>" . $target . "</b>.", E_USER_ERROR );
      router::go( '/', 404 ); 
    }

    // check that the class was loaded and that it has the correct method

    $controllerNS = '\Liriope\Controllers\\';

    if( !class_exists( $controllerNS . $controller )) {
      // check for a global namespace controller
      if(!class_exists($controller)) {
        trigger_error( "We can't find the class file <b>" . ucfirst($controller) . ".php</b>.", E_USER_ERROR );
      } else {
        $controllerNS = '';
      }
    }

    if( !method_exists( $controllerNS . $controller, $action )) {
      trigger_error( "The view <b>$action</b> doesn't seem to exist in the controller <b>$controller</b>.", E_USER_ERROR );
    }

    // Ok, run that object's function!
    $object = $controllerNS . $controller;
    $dispatch = new $object( $model, $controllerRaw, $action );
    $content = call_user_func( array( $dispatch,$action ), $getVars );
    if( $return ) return $content;
    return $dispatch;
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
  static function callController( $controller=NULL, $action=NULL, $getVars=NULL, $return=TRUE ) {
    // Controllers are uppercase on words (ex: Shovel) with "Controller" appended
    // Models are the plural of the controller (ex: Shovels)
    $C = new String($controller);
    $controllerRaw = $C->raw();
    $controller =  $C->sanatize('onlyLetters')->to_titlecase()->get();
    $model = rtrim( $controller, 's' );
    $controller .= 'Controller';
    $dispatch = self::callHook( $controller, $controllerRaw, $action, $model, $getVars );
    if( $return ) {
      return $dispatch;
    }
var_dump($dispath);
debug_print_backtrace();
exit;
    $dispatch->load();
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
    $C = new String($controller);
    $controllerRaw = $C->raw();
    $controller =  $C->sanatize('onlyLetters')->to_titlecase()->get();
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

    if( is_numeric( $url )) { $code = $url; }
    if( empty( $url )) { $url = c::get( 'root.URL', '/' ); }
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
        error_log("Attempted to visit '".uri::getRawURI()."' and was redirected to a 404 page");
        // attempt to remove the indexed file
        index::unstore(uri::get());
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
  // holds whether to use the controller or the module view
  var $use;
  var $_rule = array();
  var $_constant = array();
  var $_mandatory = array();
  var $_optional = array();

  function __construct( $name, $rule, $route, $use ) {
    $this->name = $name;
    $this->readRule( $rule );
    $this->route = $route;
    $this->use = $use;
  }

  function getUse() {
    return $this->use;
  }

  // readRule()
  // Reads the rule, breaks it apart, and sets rule info
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
