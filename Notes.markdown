Liriope Notes
=============

Changes and TODO
----------------

* Slot feature
* 404 page not showing in Chrome.

> Some research tells me that Google Chrome hijacks the 404 page and thus sends the requested URL to Google "so that they can offer search suggestions" for the page you sought. But in essence, could be gleaning sensative information.
> How can this be circumvented? Maybe by not sending the 404 header?

* Custom shortcodes

> {snippet name=|slider| folder=|home| args=|array|}
> {addStylesheet}http://url{/addStylesheet}
> {addJavascript ulr=|http://url|}
> This filter can also run page object functions like {getContent}, {set page.title "Something here"}, {page.DOCTYPE} which runs the get variable function
> logic function will also be necessary for complete removal of php from content pages.

* Navigation

> I'd like to add in navigation helpers. Maybe working towards reading folder structure to auto-generate it.

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
