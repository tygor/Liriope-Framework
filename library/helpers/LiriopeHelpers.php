<?php
/**
 * LiriopeHelpers.php
 * Base helpers for the Liriope templates
 */

function getLink( $label=NULL, $url=NULL, $args=array() )
{
  // clean input
  $label = LiriopeTools::cleanInput( $label, 'whiteAlphaNum' );

  // take the variables and send back a HTML <a> tag
  $format = '<a href="%s" %s>%s</a>';

  // take care of the arguments first
  $argString = "";
  foreach( $args as $key => $value )
  {
    $argString .= LiriopeTools::cleanInput( $key ) . '="' . LiriopeTools::cleanInput( $value ) . '" ';
  }

  return sprintf( $format, $url, $argString, $label );
}

/**
 * Snippet
 * grabs a piece of code
 */
function snippet( $name=NULL )
{
  // return nothing if they don't give us a $name
  if( empty( $name )) return false;

  // we're expecting something like "default/header" so we'll need to break it appart
  $v = explode( "/", $name );
  $controller = $v[0];
  $action = $v[1];

  callHook( $controller, $action );
}

