# Liriope
> Monkey Grass

A PHP framework for the un-controlled site.
No CMS, just MVC goodness: putting the web content
back into web pages.

@version 1.0
@author Tyler Gordon

Resources:
--------------------------------------------------------------------------------
- http://anantgarg.com/2009/03/13/write-your-own-php-mvc-framework-part-1/
- http://johnsquibb.com/tutorials/mvc-framework-in-1-hour-part-one
- http://johnsquibb.com/tutorials/mvc-framework-in-1-hour-part-two
- http://johnsquibb.com/tutorials/mvc-framework-in-1-hour-part-three

Coding Conventions:
--------------------------------------------------------------------------------
1. mySQL tables will always be lowercase and plural e.g. items, cars
2. Models will always be plural and first letter capital e.g. Items, Cars
3. Controllers will always have "Controller" appended to them. e.g. ItemController, CarController
4. Views will have plural name followed by action name as the file. e.g. items/view.php, cars/buy.php

Application Flow:
--------------------------------------------------------------------------------
web > index.php
  |
  |
  LiriopeLoad.php / LiriopeRouter.php
     . Uses function callHook() which breaks down the URL into $controller, $action, and $getVars
     . and uses call_user_func_array to call $controller::$action passing $getVars
     |
     |
     $controller::$action($getVars) : example DefaultController() -> show()
        . The __construct() function looks for a $action.php file in the views/$controller folder
        . The $action function sets default variables for that template
        . Then the __destruct function either outputs the tempalte, or stores it for output buffering
     
