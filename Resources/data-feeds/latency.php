<?php
$getDataFeedLatencyText = function($dataFeedName, $dataFeedPathName) {
    echo "<p>Data feed latency is the time it takes for a data point to travel from the source to your algorithm. The $dataFeedName data feed has a latency of ";
    echo file_get_contents(DOCS_RESOURCES."/data-feeds/latency-by-data-feed/$dataFeedPathName.php");
    echo ".</p>";
}
?>
