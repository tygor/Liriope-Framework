<?php
//
// Liriope's default configuration
// 

// Set some default router rules
router::setRule( 'blog/show', 'blog/show' );
router::setRule( 'blog/*',    'blog/post/$1' );
router::setRule( '*',         'Liriope/filepage' );

//
// Set some system defaults
// --------------------------------------------------

c::set( 'default.controller', 'liriope' );
c::set( 'default.action',     'show' );
c::set( 'default.theme',      'grass' );
c::set( 'default.404.folder', 'error' );
c::set( 'default.404.file',   '404.php' );
c::set( 'content.filetypes',  array( 'php', 'html', 'htm', 'txt' ));

//
// Set some blog defaults
// --------------------------------------------------

// set the location of the blog content relative to the web root
c::set( 'default.blog.dir', 'content/blog' );

// What will your readmore class be? This is used to divide a post
// into intro and full article text
c::set( 'default.readmore.class', 'readmore' );

// Show the intro text again in the full blog post?
c::set( 'blog.intro.show', FALSE );

// Make the title of the post in the blog list a hyperlink?
c::set( 'blog.link.title', TRUE );
?>
