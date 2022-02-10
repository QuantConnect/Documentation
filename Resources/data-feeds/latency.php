<?php
$getDataFeedLatencyText = function($dataFeedPathByDataFeed) {
    echo "<p>Live data takes time to travel from the source to your algorithm. ";
    if (sizeof($dataFeedPathByDataFeed) <= 1) {
        foreach($dataFeedPathByDataFeed as $dataFeedName => $dataFeedPathName) {
            echo "The $dataFeedName data feed has a latency of ";
            echo file_get_contents(DOCS_RESOURCES."/data-feeds/latency-by-data-feed/$dataFeedPathName.php");
        }
        echo ".</p>";
    }
    else {
        echo "The following table shows the latency of the US Equity data feeds:</p>";


        echo "<table class='qc-table table'>";
        echo     "<thead>";
        echo         "<tr>";
        echo             "<th style='width: 50%;'>Data Feed</th>";
        echo             "<th>Latency</th>";
        echo         "</tr>";
        echo     "</thead>";
        echo     "<tbody>";

        foreach($dataFeedPathByDataFeed as $dataFeedName => $dataFeedPathName) {
            echo     "<tr>";
            echo         "<td>$dataFeedName</td>";
            echo         "<td>";
            echo             file_get_contents(DOCS_RESOURCES."/data-feeds/latency-by-data-feed/$dataFeedPathName.php");
            echo         "</td>";
            echo     "</tr>";
        }

        echo     "</tbody>";
        echo "</table>";
    }
}
?>
