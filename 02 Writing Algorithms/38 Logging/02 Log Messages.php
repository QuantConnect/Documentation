<?php
include(DOCS_RESOURCES."/logging-statements/log.php");
$getLogText(false);
?>

Add "It's a good practice to add debug/log statements to algorithms running in live mode so that we can understand its behavior and keep records to compare against backtests." Some people deploy silent algorithms in live mode and when it doesn't work as expected, we don't have information to evaluate the problem. Maybe this should go as a Best Practices page in Live Trading too?
-OnEndOfAlgorithm ("intended for closing out logs")
