#!/bin/sh

# Simply run `sh watch.sh`.

# No minification
#sass --watch sass/:stylesheets/ --load-path ../../../../Liriope/Common/Sass --style expanded

# Minified
sass --watch sass/:stylesheets/ --load-path ../../../../Liriope/Common/Sass --style compressed

exit 0
