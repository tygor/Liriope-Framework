<?php

echo "\n";
echo "\nRouterTest ===================================================";
echo "\n";
echo "\nTests:";

require('../Liriope/Liriope.php');
require('../Liriope/Toolbox/Router.php');

// Create the object
echo "\nObject creation: ...................................... ";
$obj = new \Liriope\Toolbox\Router();

if( $obj instanceOf \Liriope\Toolbox\Router ) {
    echo "passed";
} else {
    echo "FAILED";
}

echo "\n\nFIN\n";

?>
