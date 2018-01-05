<?php

$page->title = 'Filters';
$page->date = '2012/06/08';

?>

<h1>Filters</h1>
<p>Great little chunks of functions stored and run during the output of the page HTML. So
much potential.</p>
<hr class="readmore">

<h1>Filters</h1>
<p>It's not a new idea, but it's a great one. To create simple funcitons that you can
attach to the View object to "filter" the HTML content before it's dumped to the page.
Wonderful for doing things like turning two dashes (--) into mdashes (&mdash;).</p>

<p>Seriously though... how else could you save your users from spam than by protecting
their plain text email address using fancy filters? Liriope comes with two at the moment.
One was simply for fun and testing, but the most recent is email obfuscation by regular
expression search and replace.</p>

<hr>

<h2>How to add a filter</h2>

<p>To add a filter, simply add a function to the PHP code. I suggest using the site
defaults.php file for ease and to consolidate. Your function should accept 1 parameter
which is the generated HTML content string. Have your function preg_ or strt or
something, and then return the new string. Finally, add one line following the function:</p>
<p><code>filter::addFilter( 'string_ID', 'function_name' );</code></p>
<p>This registers the function and it will run upon rendering of each page.</p>

<h2>To remove a filter</h2>

<p>If you want to get rid of a filter, this can be done anywhere after the filter is
registered using:</p>
<p><code>filter::removeFilter( 'string_ID' )</code></p>

<hr>

<ul>
  <li>

    <h2>Email Encode</h2>
    <p>This filter is fashioned heavily after Roel Van Gils Graceful E-Mail Obfuscation
    technique on A List Apart (<a href="http://www.alistapart.com/articles/gracefulemailobfuscation/" target="_blank">here</a>).</p>
    <p>The basic idea is that the final HTML output is removed of all
    mailto:user@site.com URLs and replaced with something that a spam bot wouldn't be
    interested in, or would have to be programed to extract. In my filter, it converts
    mailto URLs into something that my MVC structure would handle, which becomes the noJS
    fallback. And then, uses client-side javascript to turn it back into something the
    browser would understand if clicked.</p>
    <p>example:<br>
      <code>href="mail/grfg+fbzrfvgr+pbz"</code>
    </p>
    <p>If you browse the source-code, you'll see what a bot would: a nasty interal URL of
    nonsense (thanks ROT13).</p>
    <p>And for those who simply put their email in the content as plain text (inside the
    anchor tag or alone), their address is reversed in the HTML and then re-reversed
    using a span tag style attribute.</p>
    <p>example:<br>
      <code>moc.etisemos@tset</code>
    </p>

  </li>
  <li>

    <h2>Fancy Liriope</h2>
    <p>This filter was the one I was using during testing to ensure that my filter system
    was working. I'm going to leave it in place, for now. It simply wraps any literal
    "Liriope Framework" text in a span tag that I then style to make it **FANCY**.</p>

  </li>
</ul>
