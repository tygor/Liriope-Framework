<?php
// --------------------------------------------------
// filter.class.php
// --------------------------------------------------

// Direct access protection
if( !defined( 'LIRIOPE' )) die( 'Direct access is not allowed.' );

// the filter class is a holder for all the filters used on the content before it is
// rendered. This can be anything from email abstraction, curse word eliminaiton,
// or wrapping my name in <b> tags.

class filter {
  static $filters = array();

  static function addFilter( $slug, $function, $order=NULL ) {
    filter::$filters[$slug] = array(
      'func'  => $function,
      'order' => $order
    );
  }

  static function removeFilter( $slug ) {
    unset( filter::$filters[$slug] );
  }

  static function doFilters( $content=NULL ) {
    if( $content === NULL ) return "";

    filter::orderFilters();

    // run the filters
    foreach( filter::$filters as $filter ) {
      $content = call_user_func( $filter['func'], $content );
    }
    return $content;
  }

  static function orderFilters() {
    // run the filters array and arrange them based on order value
    uasort( filter::$filters, 'filter::compareOrder' );
  }

  static private function compareOrder( $a, $b ) {
    if( $a['order'] == $b['order'] ) {
      return 0;
    }
    return ( $a['order'] < $b['order'] ) ? -1 : 1;
  }

}

//
// fancyFramework
// --------------------------------------------------
// wrap any instance of "Liriope Framework" with
// a span class of fancy-framework
function fancyFramework( $c ) {
  $pattern = '/(Liriope Framework)/';
  $replacement = '<span class="fancy-framework">$1</span>';
  return preg_replace( $pattern, $replacement, $c );
}
filter::addFilter( 'fancyFramework', 'fancyFramework' );

//
// email obfuscation
// --------------------------------------------------
// seek any emails in content and convert it to something
// harder for spam bots to read
function emailIncognito( $c ) {
  $pattern = '#<a.href=\"mailto:([A-Za-z0-9._%-]+)\@([A-Za-z0-9._%-]+)\.([A-Za-z]{2,4})\"([\s]*[\w=\'"]*)>(.*)</a>#e';
  $replacement = "'<a class=\"obf\" href=\"mail/'.str::rot('$1').'+'.str::rot('$2').'+'.str::rot('$3').'\"$4>$5</a>'";
  $firstpass = preg_replace( $pattern, $replacement, $c );
  // -----
  $pattern = "#([A-Za-z0-9._%-]+)\@([A-Za-z0-9._%-]+)\.([A-Za-z]{2,4})#e";
  $replacement = "'<span style=\"unicode-bidi:bidi-override;direction:rtl;\">'.strrev('$1@$2.$3').'</span>'";
  return preg_replace( $pattern, $replacement, $firstpass );
}
filter::addFilter( 'emailIncognito', 'emailIncognito' );

?>
