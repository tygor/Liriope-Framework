<?php
//
// helpers.php
// Base helpers for the Liriope templates
//

//
// url
// build a url based
//
function url( $uri=FALSE ) {
  // strip leading and trailing slashes
  $uri = trim( $uri, '/' );
  return c::get( 'url' ) . '/' . $uri;
}

//
// snippet
// grabs a piece of page
//
// @param  string  $file The name of the file snippet
//
function snippet( $file=NULL, $params=NULL ) {
  if( $file===NULL ) return NULL;
  $path = c::get( 'root.snippets' );
  $success = load::file( $path . '/' . $file, FALSE, $params );
  if( !$success ) throw new Exception( 'Woops! Can\'t find the snippet ' . $file );
}

//
// partial()
// grabs a piece of re-usable code specific to a controller and view
//
// @param  string  $controller The name of the controller
// @param  string  $partial The name of the partial to find (appended with _)
// @param  array   $params
//
function partial( $controller=NULL, $partial=NULL, $params=NULL ) {
  if( $controller===NULL || $partial===NULL ) return NULL;
  $check = $controller.'/_'.$partial.'.php';
  $file = load::exists( $check );
  if( !$file ) return NULL;
  $success = load::file( $file, FALSE, $params );
  if( !$success ) throw new Exception( 'Woops! Can\'t find the partial ' . $file );
}

// Slugify
// replaces spaces with dashes and converts to all lowercase
//
function slugify( $input=NULL ) {
  if( empty( $input )) return false;
  $input = strtolower( tools::replaceSpaces( $input ));
  return tools::cleanInput( $input );
}

// HTML
// creates safe html
function html( $string ) {
  return str::html( $string, true );
}

// go
// redirection shortcut
function go( $url ) {
  router::go( $url );
}

?>
