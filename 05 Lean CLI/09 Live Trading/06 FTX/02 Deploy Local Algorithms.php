<?php
include(DOCS_RESOURCES."/brokerages/cli-deployment/deploy-local-algorithms.php");


$brokerageDetails = "<li>TODO</li>";
$dataFeedDetails = "";
$supportsIQFeed = false;

$getDeployLocalAlgorithmsText("FTX", $brokerageDetails, $dataFeedDetails, $supportsIQFeed);
?>