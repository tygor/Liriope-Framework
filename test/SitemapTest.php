<?php

namespace Liriope\Controllers;
use \Liriope\Colors;
use \Liriope\Expect;

echo "\n";
echo "\nSitemap ===================================================" . "\n";
echo "\n";
echo "\nTests:" . "\n";

require('TestHelper.php');

$color = new Colors();
$expect = new Expect();

// Load the Liriope framework
require('../Liriope/Liriope.php');

// ---------------------------------------------------------------------------------------------------------------------

$sitemap = new SitemapController();

echo "\nCreate a new standard Router rule: \n";
echo "- Expect " . $color->text('(string)', 'white') . " for rendered Sitemap with no locations set: " . "\n";
$expect->wantString($sitemap->show());

// ---------------------------------------------------------------------------------------------------------------------

echo $expect->getResults();
echo "---\nFIN\n\n";

?>
