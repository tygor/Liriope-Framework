# Liriope
> Monkey Grass

A PHP framework. For no reason other than to exercise and learn more of PHP and
things web.

@version 0.1
@author Tyler Gordon

Structure:
--------------------------------------------------------------------------------
Each site within the root folder is autonomous. And because of the use of the web
layer and root layer (directories) any default Liriope theme or stylesheet or anything
used by the web needs to be located within each site's web folder.

root
- liriope
- - controllers
- - models
- - views
- - library
- site
- - controllers
- - models
- - views
- - web
- - - js
- - - css
- - - images
- - - content
- - - themes
- - - snippets
- sandbox (sub-site)
- - controllers
- - models
- - views
- - web
- - - js
- - - css
- - - images
- - - content
- - - themes
- - - snippets

Resources:
--------------------------------------------------------------------------------
- https://docs.google.com/drawings/d/19wQ4Gle0dwC3jV4Wm8XhwNvMBHeu81dGyKnvRGPcQrk/edit
- http://anantgarg.com/2009/03/13/write-your-own-php-mvc-framework-part-1/
- http://johnsquibb.com/tutorials/mvc-framework-in-1-hour-part-one
- http://johnsquibb.com/tutorials/mvc-framework-in-1-hour-part-two
- http://johnsquibb.com/tutorials/mvc-framework-in-1-hour-part-three

Coding Conventions:
--------------------------------------------------------------------------------
1. Models will always be plural and first letter capital e.g. Items, Cars
2. Controllers will always have "Controller" appended to them. e.g. ItemController, CarController
3. Views will have plural name followed by action name as the file. e.g. items/view.php, cars/buy.php

