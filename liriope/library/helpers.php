<?php

use Liriope\Toolbox\Str;

//
// helpers.php
// Base helpers for the Liriope templates
//

//
// url
// build a url based
//
function url( $uri=FALSE ) {
  // is it internal?
  if(substr($uri,0,4)==='http') return $uri;
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
  if( !$success ) trigger_error( 'Woops! Can\'t find the snippet ' . $file );
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
  $html = new Str( $string );
  return $html->to_html();
}

// go
// redirection shortcut
function go( $url ) {
  router::go( $url );
}

// publish()
// template function to be used in an if block that takes two optional date/time strings
// and returns TRUE if withing that range, FALSE otherwise
//
// @param  string  $start When to begin publishing
// @param  string  $stop  When to stop publishing
// @return bool    TRUE if within the range, FALSE otherwise
function publish( $start=FALSE, $stop=FALSE ) {
  $alpha = empty( $start ) ? FALSE : strtotime( $start );
  $omega = empty( $stop ) ? FALSE : strtotime( $stop );
  $now = time();

  if( $alpha && $omega && $now >= $alpha && $now <= $omega ) return TRUE;
  if( $alpha && !$omega && $now >= $alpha ) return TRUE;
  if( !$alpha && $omega && $now <= $omega ) return TRUE;

  return FALSE;
}

// img()
// takes a guessed path to an image file and if it's not there, it searches for it
// uses the current URI to help guess
//
// @param  string  $file The path plus file name to insert
// @return string  Returns the source for <img src=""> relative to the root of the site
//
function img($file) {
  $content = c::get('root.content', 'content');
  $root = uri::toRelative($content);
  $uri = uri::get();
  // check if the image exists in a folder named after the uri
  $path = "$root/$uri/$file";
  if(file_exists($content . '/' . $uri . '/' . $file)) return $path;
  // also check the parent folder for content pages not using index.php within a folder
  $uri = explode('/',$uri);
  array_pop($uri);
  $uri = implode('/',$uri);
  $path2 = "$root/$uri/$file";
  if(file_exists($content . '/' . $uri . '/' . $file)) return $path2;
  trigger_error("We couldn't guess the location for $file. We looked in $path and $path2", E_USER_WARNING);
  return false;
}

?>
