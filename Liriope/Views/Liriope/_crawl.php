<?php

$duration = microtime() - $module->start / 1000;

echo "Visited $module->crawled pages.\n";
echo "In $duration seconds\n";

// The final output is trimmed, so any trailing newline or spaces are removed.
echo "\n.";

?>
