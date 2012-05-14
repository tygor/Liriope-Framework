Liriope Notes
=============

Changes and TODO
----------------

* Slot feature

* Custom shortcodes

> {snippet name=|slider| folder=|home| args=|array|}
> {addStylesheet}http://url{/addStylesheet}
> {addJavascript ulr=|http://url|}
> This filter can also run page object functions like {getContent}, {set page.title "Something here"}, {page.DOCTYPE} which runs the get variable function
> logic function will also be necessary for complete removal of php from content pages.

* Navigation

> I'd like to add in navigation helpers. Maybe working towards reading folder structure to auto-generate it.

* Check into the configuration class and the page class "set" functions

> Refractoring the config class, and error testing this change, helped me realize that the page class holds it's own variables and that they overlap. So, who gets what?! Why have two classes do the same thing?!

> There is a ton of disorganization! Just look in liriope > lib/controllers/models and such. Fix this!

> Is it possible to bring themes, snippets, and plugins into the root? This way, each "site" can use the same theme

* liriope/library/load.class.php:72:        // TODO: uh... why do I load themes here? Shouldn't this be outside of the load class, like in the view creation process? At the very least, these would be auto_loaded like any controller
* liriope/library/router.class.php:22:      // TODO: My goal is to relay to direct files but capture the controller/action for content files. Sadly, content images are stored under the content folder (perhaps a problem) so what I'm truly doing is checking for specific extensions and allowing them by extension.
* liriope/library/router.class.php:27:      // TODO: check extension against accepted pass-through extensions then go() to them
* liriope/models/Blogposts.class.php:49:    // TODO: Ew! this is horrible. Each blog page sets a pubdate, but it sets the "page" class with the date. So, when grabbing the intro text... how should I grab the pubDate? Maybe I need to do some text parsing rather than setting a PHP class object. a la Kirby.
* liriope/models/Blogs.class.php:74:        // TODO: this is hairy, but works:
* liriope/models/Files.class.php:101:       // TODO: this could return the query string after the file if one exists
* liriope/models/Folderfile.class.php:29:   // TODO: files that throw a WARNING when trying to be included seem to get past my error checking
* liriope/models/Folderfile.class.php:115:  // TODO: if the above works well, remove below
* liriope/models/Tumblr.class.php:189:      // TODO: create a class to catch photosets, a sub-variety of the photo type
* liriope/models/Tumblr.class.php:256:      // TODO setup classes for any other Tumlbr post types with their own unique values
* site/web/content/error/404.php:3:         // TODO: create a .css version of the LESSCSS error styles

Config variables
----------------

* development
* root              - /
* root.liriope      - ../
* root.web          - ../web
* root.content      - /content
* root.content.file - index.php
* root.theme
* root.snippets
* page.title
* page.DOCTYPE
* page.decription
* page.author
* default.controller
* default.action
* default.theme

Thinking through the page
-------------------------

HTML = Page + Theme
Theme = Theme
Page = Controller + View

HTML = Theme + Controller + View
