<?php
#
# Liriope's default configuration
# 

# Set some system defaults
c::set( 'default.controller', 'liriope' );
c::set( 'default.action',     'show' );
c::set( 'default.theme',      'grass' );
c::set( 'default.404.folder', 'error' );
c::set( 'default.404.file',   '404.php' );
c::set( 'default.blog.dir',   'content/blog' );
c::set( 'readmore.class',     'readmore' );
c::set( 'content.filetypes',  array( 'php', 'html', 'htm', 'txt' ));

?>
