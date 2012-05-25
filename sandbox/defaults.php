<?php

//
// Set your site default configuration in this file
// 

//
// Custom Routes
// --------------------------------------------------
router::setRule( 'mediapost', 'media/*',      'blog/post/dir/media/$1' );
router::setRule( 'mediarss2', 'media/feed/*', 'blog/feed/dir/media/$1' );
router::setRule( 'mediarss',  'media/feed',   'blog/feed/dir/media' );
router::setRule( 'media2',    'media/show/*', 'blog/show/dir/media' );
router::setRule( 'media',     'media',        'blog/show/dir/media' );
router::setRule( 'newspost',  'news/*',       'blog/post/dir/news/$1' );
router::setRule( 'newsrss2',  'news/feed/*',  'blog/feed/dir/news/$1' );
router::setRule( 'newsrss',   'news/feed',    'blog/feed/dir/news/' );
router::setRule( 'news2',     'news/show/*',  'blog/show/dir/news' );
router::setRule( 'news',      'news',         'blog/show/dir/news' );

//
// Site 
// --------------------------------------------------
c::set( 'url', 'http://sandbox.ubuntu' );
c::set( 'site.title', 'NRHC' );

//
// Page
// --------------------------------------------------
c::set( 'page.title', 'North Rock Hill Church' );

//
// Theme
// --------------------------------------------------
c::set( 'theme', 'nrhc' );

//
// Debugging
// --------------------------------------------------
c::set( 'debug', TRUE );

?>
