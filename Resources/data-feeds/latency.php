<?php
$getDataFeedLatencyText = function($dataFeedName, $dataFeedPathName) {
    echo "<p>Data feed latency is the amount of time it takes for a data point in the data feed to travel to your algorithm. The $dataFeedName data feed has a latency of ";
    echo file_get_contents(DOCS_RESOURCES."/data-feeds/latency-by-data-feed/$dataFeedPathName.php");
    echo ".</p>";
}
?>
