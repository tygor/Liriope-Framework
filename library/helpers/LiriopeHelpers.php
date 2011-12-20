<?php
/**
 * LiriopeHelpers.php
 * Base helpers for the Liriope templates
 */

function getLink( $label=NULL, $url=NULL, $args=array() )
{
  // clean input
  $label = LiriopeTools::cleanInput( $label );

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
