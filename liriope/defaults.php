<?php
//
// Liriope's default configuration
// 

// Set some default router rules
router::setRule( 'home',      '*',           'liriope/show' ); // catchall rule
router::setRule( 'gallery',   'gallery',     'gallery/show' );
router::setRule( 'image',     'gallery/!id', 'gallery/image/$id' );
router::setRule( 'flush',     'flush',       'liriope/flush' );
router::setRule( 'mail',      'mail/:rot13', 'liriope/mail' );
router::setRule( 'blog',      'blog',        'blog/show' );
router::setRule( 'blogpost',  'blog/!id',    'blog/post/id/$id' );
router::setRule( 'bloglist',  'blog/show',   'blog/show' );
router::setRule( 'blogfeed',  'blog/feed',   'blog/feed' );

//
// Set some system defaults
// --------------------------------------------------

c::set( 'home', 'home' );
c::set( 'url', 'http://liriope.ubuntu' );
c::set( 'default.controller', 'liriope' );
c::set( 'default.action',     'show' );
c::set( 'theme',              'grass' );
c::set( 'theme.folder',       'themes' );
c::set( 'default.404.folder', 'error' );
c::set( 'default.404.file',   '404.php' );
c::set( 'content.filetypes',  array( 'php', 'html', 'htm', 'txt' ));
c::set( 'context', 'render' );
c::set( 'cache', TRUE );

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
?>
