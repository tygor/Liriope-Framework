# Liriope
> Monkey Grass

A PHP framework. For no reason other than to exercise and learn more of PHP and
things web.

Liriope is a web backend for people who know PHP, HTML, CSS, and any other frontend code.
It's for us who write the web. It stemmed from a learning project, but as the project
got better, I decided to use it here and there. Now, it's live on a few sites which is awesome
and horrible at the same time. Why? Because now I have issues and bugs that I want to fix.

## The name
As it goes with code projects, they need a name, and generally a person chooses something silly
just to get it named, not expecting it to thrive and become public. Well, Liriope isn't famous,
and it's name means nothing. It was a silly title, and it worked. I chose it for the
abundant flora in my back yard. And who doesn't like monkeys?

- @version 0.1 BETA
- @author Tyler Gordon

##Structure:

Each site within the root folder is autonomous. And because of the use of the web
layer and root layer (directories) on most Linux web hosts any default Liriope theme or stylesheet or anything
used by the web needs to be located within each site's web folder.

###Suggested structure:

- root
  - liriope/  : the brains
  - site-config/ : the site settings and MVC overrides
  - public_html/ : default web layer
    - site/ : The web layer
      - css/
      - js/
      - images/
      - index.php

##Resources:

- http://anantgarg.com/2009/03/13/write-your-own-php-mvc-framework-part-1/
- http://johnsquibb.com/tutorials/mvc-framework-in-1-hour-part-one
- http://johnsquibb.com/tutorials/mvc-framework-in-1-hour-part-two
- http://johnsquibb.com/tutorials/mvc-framework-in-1-hour-part-three

