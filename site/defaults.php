<?php

//
// Set your site default configuration in this file
// 

// Custom Routes
// --------------------------------------------------
router::setRule( 'docs', 'docs/:page/:limit', 'blog/show/dir/docs/page/$page/limit/$limit' );
router::setRule( 'doc',  'docs/!id',          'blog/post/dir/docs/id/$id' );

//
// Site 
// --------------------------------------------------
c::set( 'url', 'http://liriope.ubuntu' );
c::set( 'site.title', 'Liriope' );

//
// Theme
// --------------------------------------------------
c::set( 'theme', 'grass' );

//
// Debugging
// --------------------------------------------------
c::set( 'debug', TRUE );
c::set( 'cache', FALSE );
?>
