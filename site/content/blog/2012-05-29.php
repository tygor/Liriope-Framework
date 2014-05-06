<?php

$page->title = 'Modules and partials';
$page->date = '2012-05-29';

?>

<h1><?php echo $page->title() ?></h1>
<hr class='readmore'>

<aside>
  <?php // TODO: This creates an infinate loop! Getting a list of blogs from a blog article ?>
  <?php // echo module( 'blog', 'show', array( 'limit' => 4 )) ?>
</aside>

<h1><?php echo $page->title() ?></h1>
<p>So now I have a blog. Good. But like most sites, what if I want to show the top 5 articles
in an aside or something on the homepage? How do I do that?</p>
<p>It's not a new idea. I stole it. But it's my own implementation, so I'm not totally a thief.
I'm using something similar to Symfony's components and partials. I call my Modules because
Component and Controller are spelled to similarly.</p>
<p>Well, it's working now. The little blog list on the homepage is my module at work.
It's also using the power of the View (though this needs to be refractored&mdash;for now
I've duped the code in the LiriopeModule class). Anyway, now all you have to do from a page
is call module('controller name', 'action name', 'array of paramaters') and [French word for here]!</p>
<p>Some future ideas I have for this would be snatching the timecodes for the last number of articles
and creating a dynamic activity bar graph. Or, maybe in the future, pulling the "tags" out
for a tag cloud creation. Ooh, or maybe a media module for podcasts and such! Who knows!</p>
