<?php
include(DOCS_RESOURCES."/brokerages/cli-deployment/deploy-local-algorithms.php");


$brokerageDetails = "";
$dataFeedDetails = "
	<p>
	    If your algorithm only uses custom data, you can select the \"Custom data only\" data feed option.
	    This data feed doesn't require any brokerage credentials, but only works if your algorithm doesn't subscribe to non-custom data.
	    Your algorithm crashes if it attempts to subscribe to non-custom data with this data feed in place.
	</p>
";
$supportsIQFeed = true;

$getDeployLocalAlgorithmsText("QuantConnect Paper Trading", $brokerageDetails, $dataFeedDetails, $supportsIQFeed);
?>