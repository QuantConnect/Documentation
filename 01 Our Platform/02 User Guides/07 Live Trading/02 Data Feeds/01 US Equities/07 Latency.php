<?php 
include(DOCS_RESOURCES."/data-feeds/latency.php"); 
$dataFeedPathByDataFeed = array("US Equities" => "us-equities",
                                "US Equity Security Master" => "us-equity-security-master",
                                "US Fundamentals" => "us-fundamentals",
                                "US Equities Short Availability" => "us-equities-short-availability");
$getDataFeedLatencyText($dataFeedPathByDataFeed);
?>