<?php 
echo file_get_contents(DOCS_RESOURCES."/data-feeds/bar-building.html"); 
echo file_get_contents(DOCS_RESOURCES."/datasets/live-and-backtest-differences.html");
?>

<?php
include(DOCS_RESOURCES."/data-feeds/latency.php"); 
$getDataFeedLatencyText("");
?>