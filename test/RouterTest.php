<?php

namespace Liriope;

use Liriope\Toolbox\Router;

echo "\n";
echo "\nRouterTest ===================================================" . "\n";
echo "\n";
echo "\nTests:" . "\n";

require('TestHelper.php');

$color = new Colors();
$expect = new Expect();

function doCURL($url) {
    // create a new cURL resource
    $ch = curl_init();

    // set URL and other options
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    // grab URL and pass it to the browser
    curl_exec($ch);

    // get the response headers
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    // close cURL resource
    curl_close($ch);

    return $httpcode;
}

require('../Liriope/Liriope.php');

// ------------------------
echo "\nCreate a new standard Router rule: \n";
echo "- Expect " . $color->text('(bool) TRUE', 'white') . " for successful Router route creation: " . "\n";
$expect->wantBool(Router::setRule('test', 'test/route/:optional/*', 'liriope/show', 'controller'), true);

$rule = Router::getRule('test');

echo "- Expect route name to be " . $color->text("(object) \\Liriope\\Toolbox\\RouterRule", 'white') . ": " . "\n";
$expect->wantObject($rule, '\\Liriope\\Toolbox\\routerRule');

echo "- Expect route name to be " . $color->text("'test'", 'white') . ": " . "\n";
$expect->wantString($rule->name, 'test');

echo "- Expect route name to NOT be " . $color->text("'foo'", 'white') . ": " . "\n";
$expect->wantString($rule->name, 'foo', 0);

// ------------------------
echo "\nCreate a new closure Router rule: \n";
// This closure route is not accessable through CURLs since it's configuration is only here and not in any HTTP reachable site.
Router::setRule( 'func', 'func/show', function() { echo "this is a closure route"; });
$rule2 = Router::getRule('func');

echo "- Expect route to be an " . $color->text('(object) Closure', 'white') . ": " . "\n";
$expect->wantObject(Router::getDispatch($rule2->_rule), 'Closure');

echo "- Expect route rule name to be " . $color->text('func', 'white') . ": " . "\n";
$expect->wantString(router::rule('func'), 'func/show');

// ------------------------
echo "\nVisit URLs and expect different responses: \n";

echo "- Expect http://liriope.ubun/ response to be a " . $color->text('(int) 200', 'white') .": " . "\n";
$expect->wantInteger(doCURL('http://liriope.ubun'), 200);

// The test/closure route exists in Liriope and is so reachable through HTTP requests
$testURL = "http://liriope.ubun/test/closure";
echo "- Expect ".$testURL." response to be a " . $color->text('(int) 200', 'white') .": " . "\n";
$expect->wantInteger(doCURL($testURL), 200);


echo $expect->getResults();
echo "---\nFIN\n\n";

?>
