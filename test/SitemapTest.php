<?php

namespace Liriope\Component\Test;
use Liriope\Controllers\SitemapController;
require('../Liriope/Component/Test/TestHelper.php');

echo "\n";
echo "\nSitemap ===================================================" . "\n";
echo "\n";
echo "\nTests:" . "\n";

$color = new Colors();
$expect = new Expect();

// Load the Liriope framework
require('../Liriope/Liriope.php');

// ---------------------------------------------------------------------------------------------------------------------
// Show tests

$sitemap = new SitemapController();

echo "\nCreate a new Sitemap controller: \n";
echo "- Expect " . $color->text('(string)', 'white') . " for rendered Sitemap with no locations set: " . "\n";
$expect->wantString($sitemap->show());

// ---------------------------------------------------------------------------------------------------------------------
// SetFilename tests

echo "\nTry to set a new filename to use: \n";
echo "- Expect " . $color->text('(boolean)', 'white') . "\n";
$expect->wantBool($sitemap->setFilename('sitemap-test.xml'), true);

echo "\nNow set the filename wrong. Don't pass an extension: \n";
echo "- Expect " . $color->text('(boolean)', 'white') . "\n";
$expect->wantBool($sitemap->setFilename('sitemap-test'), false);

echo "\nNow set the filename wrong. Try to pass only an extension: \n";
echo "- Expect " . $color->text('(boolean)', 'white') . "\n";
$expect->wantBool($sitemap->setFilename('.xml'), false);

echo "\nWhat if I pass a file with a path: \n";
echo "- Expect " . $color->text('(boolean)', 'white') . "\n";
$expect->wantBool($sitemap->setFilename('test/sitemap-test.xml'), true);

// ---------------------------------------------------------------------------------------------------------------------
// Save tests

echo "\nTry to save the sitemap file: \n";
echo "- Expect " . $color->text('(boolean)', 'white') . "\n";
$sitemap->setFilename('sitemap-test.xml');
$expect->wantBool($sitemap->save(), true);

echo "\nTry to save a file that (hopefully) doesn't exist: \n";
echo "- Expect " . $color->text('(boolean)', 'white') . "\n";
$sitemap->setFilename('sitemap-abcd.xml');
$expect->wantBool($sitemap->save(), true);

echo "\nNow delete that file for next tests: \n";
echo "- Expect " . $color->text('(boolean)', 'white') . "\n";
$expect->wantBool($sitemap->remove(), true);

// ---------------------------------------------------------------------------------------------------------------------

echo $expect->getResults();
echo "---\nFIN\n\n";

?>
