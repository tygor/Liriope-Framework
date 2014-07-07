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

$controller = new SitemapController();

echo "\nCreate a new Sitemap controller: \n";
echo "- Expect " . $color->text('(string)', 'white') . " for rendered Sitemap with no locations set: " . "\n";
$expect->wantString($controller->show());

// ---------------------------------------------------------------------------------------------------------------------
// SetFilename tests

echo "\nTry to set a new filename to use: \n";
echo "- Expect " . $color->text('(boolean)', 'white') . "\n";
$expect->wantBool($controller->setFilename('sitemap-test.xml'), true);

echo "\nNow set the filename wrong. Don't pass an extension: \n";
echo "- Expect " . $color->text('(boolean)', 'white') . "\n";
$expect->wantBool($controller->setFilename('sitemap-test'), false);

echo "\nNow set the filename wrong. Try to pass only an extension: \n";
echo "- Expect " . $color->text('(boolean)', 'white') . "\n";
$expect->wantBool($controller->setFilename('.xml'), false);

echo "\nWhat if I pass a file with a path: \n";
echo "- Expect " . $color->text('(boolean)', 'white') . "\n";
$expect->wantBool($controller->setFilename('test/sitemap-test.xml'), true);

// ---------------------------------------------------------------------------------------------------------------------
// Read tests

$controller->setFilename('sitemap-test.xml');

echo "\nGet the current model filename: \n";
echo "- Expect " . $color->text('(string)', 'white') . "\n";
$expect->wantString($controller->model->getFilename(), 'sitemap-test.xml');

echo "\nClear the model so following tests are on a clean slate: \n";
echo "- Expect " . $color->text('(boolean','white') . "\n";
$expect->wantBool($controller->clearPages(), true);

echo "\nAdd a new page: \n";
echo "- Expect " . $color->text('(boolean)', 'white') . "\n";
$expect->wantInteger($controller->addPage('http://liriope.ubun/home'), 1);

echo "\nAdd a second new page: \n";
echo "- Expect " . $color->text('(boolean)', 'white') . "\n";
$expect->wantInteger($controller->addPage('http://liriope.ubun/about-us'), 2);

// ---------------------------------------------------------------------------------------------------------------------
// Save tests

echo "\nTry to save the sitemap file: \n";
echo "- Expect " . $color->text('(boolean)', 'white') . "\n";
$expect->wantBool($controller->save(), true);

echo "\nTry to save a file that (hopefully) doesn't exist: \n";
echo "- Expect " . $color->text('(boolean)', 'white') . "\n";
$controller->setFilename('sitemap-abcd.xml');
$expect->wantBool($controller->save(), true);

echo "\nNow delete that file for next tests: \n";
echo "- Expect " . $color->text('(boolean)', 'white') . "\n";
$expect->wantBool($controller->remove(), true);

// ---------------------------------------------------------------------------------------------------------------------

echo $expect->getResults();
echo "---\nFIN\n\n";

exit;

?>
