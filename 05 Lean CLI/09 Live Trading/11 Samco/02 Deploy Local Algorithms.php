<?php
include(DOCS_RESOURCES."/brokerages/cli-deployment/deploy-local-algorithms.php");


$brokerageDetails = "TODO";
$dataFeedDetails = "";
$supportsIQFeed = false;

$getDeployLocalAlgorithmsText("Samco", $brokerageDetails, $dataFeedDetails, $supportsIQFeed);
?>