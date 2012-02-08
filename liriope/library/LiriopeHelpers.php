<?php
/**
 * LiriopeHelpers.php
 * Base helpers for the Liriope templates
 */

/* --------------------------------------------------
 * getLink
 * --------------------------------------------------
 * takes the arguments and spits out an anchor tag link
 *
 */
function getLink( $label=NULL, $url=NULL, $args=array() ) {
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

/* --------------------------------------------------
 * Snippet
 * --------------------------------------------------
 * grabs a piece of page
 *
 */
function snippet( $file=NULL ) {
  if( $file===NULL ) return NULL;
  $path = c::get( 'root.snippets' );
  $success = load::file( $path . '/' . $file );
  if( !$success ) throw new Exception( 'Woops! Can\'t find the snippet ' . $file );
}

/* --------------------------------------------------
 * Slugify
 * --------------------------------------------------
 * replaces spaces with dashes and converts to all lowercase
 *
 */
function slugify( $input=NULL ) {
  if( empty( $input )) return false;
  $input = strtolower( LiriopeTools::replaceSpaces( $input ));
  return LiriopeTools::cleanInput( $input );
}

