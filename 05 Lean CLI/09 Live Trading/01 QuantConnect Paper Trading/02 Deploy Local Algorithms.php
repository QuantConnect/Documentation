<?php
include(DOCS_RESOURCES."/brokerages/cli-deployment/deploy-local-algorithms.php");


$brokerageDetails = "";
$dataFeedDetails = "
        <p>
            If your algorithm only uses custom data, you can select the \"Custom data only\" data feed option.
            This data feed doesn't require any brokerage credentials, but only works if your algorithm doesn't subscribe to non-custom data.
            Your algorithm crashes if it attempts to subscribe to non-custom data with this data feed in place, including the benchmark security.
            To avoid data issues with the benchmark security, either set the benchmark to the subscribed custom data or a constant.
        </p>
        <div class='section-example-container'>
            <pre class='csharp'>SetBenchmark(x => 0);</pre>
            <pre class='python'>self.SetBenchmark(lambda x: 0)</pre>
        </div>
";
$supportsIQFeed = true;

$getDeployLocalAlgorithmsText("QuantConnect Paper Trading", $brokerageDetails, $dataFeedDetails, $supportsIQFeed);
?>
