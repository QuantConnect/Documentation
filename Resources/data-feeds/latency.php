<?php
$getDataFeedLatencyText = function($dataFeedPathByDataFeed) {
    echo "<p>Data feed latency is the time it takes for a data point to travel from the source to your algorithm. ";
    if (sizeof($dataFeedPathByDataFeed) > 1) {
        echo "</p>";
    }

    foreach($dataFeedPathByDataFeed as $dataFeedName => $dataFeedPathName) {
        if (sizeof($dataFeedPathByDataFeed) > 1) {
            echo "<h4>$dataFeedName</h4>";
            echo "<p>";
        }
        echo "The $dataFeedName data feed has a latency of ";
        echo file_get_contents(DOCS_RESOURCES."/data-feeds/latency-by-data-feed/$dataFeedPathName.php");
        echo ".</p>";
    }
}
?>
