<?php
//
// Liriope's default configuration
// 

date_default_timezone_set('America/New_York');

// Set some default router rules
router::setRule( 'home',        '*',           'liriope/show' ); // catchall rule
router::setRule( 'gallery',     'gallery',     'gallery/show' );
router::setRule( 'image',       'gallery/!id', 'gallery/image/$id' );
router::setRule( 'crawl',       'crawl',       'liriope/crawl' ); // crawl the site and index page content
router::setRule( 'flush',       'flush',       'liriope/flush' ); // flush the cache
router::setRule( 'search',      'search/*',    'liriope/search' );
router::setRule( 'mail',        'mail/:rot13', 'liriope/mail' );
router::setRule( 'form',        'form',        'form/show' );
router::setRule( 'formpost',    'form/!id/*',  'form/get/id/$id' );
router::setRule( 'formlist',    'form/show',   'form/show' );
router::setRule( 'formprocess', 'form/submit', 'form/submit' );
router::setRule( 'formsuccess', 'form/success','form/success' );
router::setRule( 'formerror',   'form/error',  'form/error' );
router::setRule( 'blog',        'blog',        'blog/show' );
router::setRule( 'blogpost',    'blog/!id/*',  'blog/post/id/$id' );
router::setRule( 'bloglist',    'blog/show',   'blog/show' );
router::setRule( 'blogfeed',    'blog/feed',   'blog/feed' );

//
// Set some system defaults
// --------------------------------------------------

c::set( 'home', 'home' );
c::set( 'url', 'http://liriope.ubun' );
c::set( 'default.controller', 'liriope' );
c::set( 'default.action',     'show' );
c::set( 'theme',              'grass' );
c::set( 'theme.folder',       'themes' );
c::set( 'default.404.folder', 'error' );
c::set( 'default.404.file',   '404.php' );
c::set( 'load.filetypes',  array( 'php', 'html', 'htm', 'txt', 'yml', 'yaml' ));
c::set( 'context', 'render' );
c::set( 'cache', TRUE );
c::set( 'cache.expiration', (24*60*60));
c::set( 'index', TRUE );
c::set( 'index.multiplier', 3 );
c::set( 'form.folder', 'data/forms' );
c::set( 'date.format', 'l, F jS, Y');

//
// Set some site defaluts
// --------------------------------------------------
c::set( 'site.DOCTYPE', '<!DOCTYPE html>' );
c::set( 'site.title', 'Liriope' );
c::set( 'site.description', 'A learning PHP framework.' );

//
// Set some page defaluts
// --------------------------------------------------
c::set( 'page.title', 'Monkey Grass' );
c::set( 'page.description', 'A learning project called Liriope.' );
c::set( 'page.author', 'Tyler Gordon' );

//
// Set some blog defaults
// --------------------------------------------------

// set the location of the blog content relative to the web root
c::set( 'blog.dir', 'blog' );

// What will your readmore class be? This is used to divide a post
// into intro and full article text
c::set( 'default.readmore.class', 'readmore' );

// Show the intro text again in the full blog post?
c::set( 'blog.intro.show', FALSE );

// Make the title of the post in the blog list a hyperlink?
c::set( 'blog.link.title', TRUE );

//
// FILTERS
// --------------------------------------------------

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
  $email_pattern = '([A-Za-z0-9._%-]+)\@([A-Za-z0-9._%-]+)\.([A-Za-z]{2,4})';
  $pattern = '#<a([^>]*href=[\'"]mailto:(.*)[\'"][^>]*)>.*</a>#iU';
  preg_match_all( $pattern, $c, $matches );
  // save the class settings
  $classes = array();
  foreach($matches[1] as $k=>$m) {
    preg_match('/class=[\'"](.*)[\'"]/iU', $m, $match);
    $classes[$k] = !empty($match[1]) ? explode( ' ', trim($match[1])) : '';
    $classes[$k][] = 'obf';
  }
  // save the href settings
  $emails = array();
  foreach($matches[2] as $k=>$m) {
    preg_match('/'.$email_pattern.'[\?]*(.*)/i', $m, $match); 
    $emails[$k] = $match;
  }
  // remove/replace the href and class
  $anchors = array();
  foreach( $matches[0] as $k=>$m) {
    $class = trim(implode(' ', $classes[$k]));
    $one = new String($emails[$k][1]);
    $one = $one->rot()->get();
    $two = new String($emails[$k][2]);
    $two = $two->rot()->get();
    $three = new String($emails[$k][3]);
    $three = $three->rot()->get();
    $email = "$one+$two+$three";
    if($emails[$k][4]) $email = $email . '?' . $emails[$k][4];
    $strip = preg_replace('/\s*href=[\'"].*[\'"]|\s*class=[\'"].*[\'"]/iU', '', $m);
    $anchors[$k] = preg_replace('/<a(.*)/iU', '<a href="/mail/'.$email.'" class="'.$class.'" $1', $strip);
  }
  // now replace each in the content
  $firstpass = $c;
  foreach($matches[0] as $k=>$m) {
    $firstpass = preg_replace( "/".preg_quote($m,'/')."/", $anchors[$k], $firstpass );
  }
  // -----
  $pattern = "#".$email_pattern."#";
  return preg_replace_callback( $pattern, function($matches) {
      return '<span style="unicode-bidi:bidi-override;direction:rtl;">'.strrev($matches[0]).'</span>';
      var_dump($matches);
  }, $firstpass );
}
filter::addFilter( 'emailIncognito', 'emailIncognito' );

?>
