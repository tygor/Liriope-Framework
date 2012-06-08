<?php

//
// Set your site default configuration in this file
// 

//
// Custom Routes
// --------------------------------------------------
router::setRule( 'media',     'media',      'blog/show/dir/media' );
router::setRule( 'mediaID',   'media/!id',  'blog/post/dir/media/id/$id' );
router::setRule( 'medialist', 'media/show', 'blog/show/dir/media' );
router::setRule( 'mediafeed', 'media/feed', 'blog/feed/dir/media' );

router::setRule( 'news',     'news',      'blog/show/dir/news' );
router::setRule( 'article',  'news/!id',  'blog/post/dir/news/id/$id' );
router::setRule( 'newslist', 'news/show', 'blog/show/dir/news' );
router::setRule( 'newsfeed', 'news/feed', 'blog/feed/dir/news' );

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
c::set( 'debug', FALSE );

?>
