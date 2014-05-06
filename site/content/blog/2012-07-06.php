<?php

$page->title = 'And now, Search';
$page->date = '2012-07-06';

?>

<h1><?php echo $page->title() ?></h1>
<p>As development continues on Liriope and and I use it for this site and others,
I keep thinking of features that are a must for version 1.0. Search was a
daunting one that I was afraid to tackle. It is partially done, working well,
and it wasn't as hard as I thought.</p>

<hr class="readmore">

<h1><?php echo $page->title() ?></h1>
<p>As development continues on Liriope and and I use it for this site and others,
I keep thinking of features that are a must for version 1.0. Search was a
daunting one that I was afraid to tackle. It is partially done, working well,
and it wasn't as hard as I thought.</p>

<p>Some hurdles to tackle:</p>
<ol>
  <li>Capturing the <strong>page title</strong> to display in the search results</li>
  <li>Grabbing an <strong>excerpt</strong> around the sought after word so that the results show
  a glimpse of the page it found</li>
  <li>Creating a <strong>command line population</strong> script to use in a chron job to crawl the site</li>
</ol>

<p>I&rsquo;m not sure how to tackle these extra features, but I wasn't sure how to
do the search either. Granted, there was help to be had for the bulk of the search
logic, but I think I can get it done.</p>

<hr>
<h2>Give it a try:</h2>
<?php snippet( 'searchform.php' ) ?>
<hr>
