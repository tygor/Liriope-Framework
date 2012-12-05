# Liriope
> Liriope is commonly known as Monkey Grass or Spider Grass

A PHP framework. For no reason other than to exercise and learn more of PHP and all
things web.

Liriope is a web backend for people who know PHP, HTML, CSS, and any other code.
It's for us who write the web. WYSIWYG consumers beware!

It stemmed from a learning project as I wrote above, but as the project grew it became a viable framework for the sites
that I manage. Now, it's live on these sites which is awesome and horrible at the same time. Why? Because now I have
issues and bugs that I want to fix. Do you want to expand your knowledge? Join me on Liriope!

## The name

As it goes with code projects, they need a name, and generally a person chooses something silly just to get it named,
not expecting it to thrive and become public. Well, Liriope isn't famous, and its name means nothing. It was a silly
title, and it worked. I chose it for the abundant flora in my back yard. And who doesn't like monkeys, or grass?

- @version 0.2 BETA
- @author Tyler Gordon

##Structure

I have designed Liriope so that each site can point to the liriope folder. This means that you can run one installation
of Liriope on multiple sites within the same host, and I do. This also means that you can install various tags or commits
of Liriope and have each site use a different one. À la carte style!

This also means that you can install the liriope brains behind the www or public_html wall.

###Suggested structure

- root
  - liriope/  : the brains
  - site-config/ : the site settings and MVC overrides
  - public_html/ : default web layer
    - site/ : The web layer
      - css/
      - js/
      - images/
      - index.php

