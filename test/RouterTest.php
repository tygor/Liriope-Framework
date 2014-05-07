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

require('../Liriope/Liriope.php');

// ------------------------
echo "\nCreate a new standard Router rule: \n";
echo "- Expect " . $color->text('(bool) TRUE', 'white') . " for successful Router route creation: " . "\n";
$expect->wantBool(Router::setRule('test', 'test/route/:optional/*', 'liriope/show', 'controller'), true);

$rule =  \Liriope\Toolbox\Router::getRule('test');

echo "- Expect route name to be " . $color->text("(object) \\Liriope\\Toolbox\\RouterRule", 'white') . ": " . "\n";
$expect->wantObject($rule, '\\Liriope\\Toolbox\\routerRule');

echo "- Expect route name to be " . $color->text("'test'", 'white') . ": " . "\n";
$expect->wantString($rule->name, 'test');

echo "- Expect route name to NOT be " . $color->text("'foo'", 'white') . ": " . "\n";
$expect->wantString($rule->name, 'foo', 0);

// ------------------------
echo "\nCreate a new closure Router rule: \n";
\Liriope\Toolbox\Router::setRule('func', 'func/*', function() { echo "this is a closure route"; });
$rule2 = Router::getRule('func');

echo "- Expect route to be an " . $color->text('(object) Closure', 'white') . ": " . "\n";
$expect->wantObject(Router::getDispatch($rule2->_rule), 'Closure');

echo $expect->getResults();
echo "---\nFIN\n\n";

?>
