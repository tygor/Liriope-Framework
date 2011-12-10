<?php
/**
 * LiriopeTools.class.php
 */

class LiriopeTools
{
  public static function devPrint ( $var = NULL, $dump = false )
  {
    if( empty( $var ) ) return false;
    echo( '<pre>' );
    if( $dump ) var_dump( $var );
    else print_r( $var );
    echo( '</pre>' );
  }

  /**
   * cleanInput
   * removes or changes characters from $input so that it's nicer
   * to work with or safe from stray symbols
   *
   * options: alphaOnly, alphaNumeric
   */
  public static function cleanInput ( $input, $options = 'alphaNumeric' )
  {
    $cleaned = '';

    switch ( $options )
    {
      case 'alphaOnly':
        $pattern = '#[^a-z]*#i';
        $replacement = '';
        break;
      default:
      case 'alphaNumeric':
        $pattern = '#[^a-z0-9-_]*#i';
        $replacement = '';
        break;
    }

    $cleaned = preg_replace( $pattern, $replacement, $input );
    return $cleaned;
  }
}

?>
