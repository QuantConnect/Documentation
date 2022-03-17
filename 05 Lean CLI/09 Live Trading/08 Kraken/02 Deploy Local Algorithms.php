<?php
include(DOCS_RESOURCES."/brokearges/cli-deployment/deploy-local-algorithms.php");


$brokerageDetails = "TODO";
$dataFeedDetails = "";
$supportsIQFeed = false;

$getDeployLocalAlgorithmsText("Kraken", $brokerageDetails, $dataFeedDetails, $supportsIQFeed);
?>