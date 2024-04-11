<?
$brokerageName = "QuantConnect Paper Trading";
$dataFeedName = "";
$isBrokerage = true;
$brokerageDetails = "";
$dataFeedDetails = "
        <p>
            If your algorithm only uses custom data, you can select the \"Custom data only\" data provider option.
            This data feed doesn't require any brokerage credentials, but only works if your algorithm doesn't subscribe to non-custom data.
            Your algorithm crashes if it attempts to subscribe to non-custom data with this data provider in place, including the benchmark security.
            To avoid data issues with the benchmark security, either <a href='https://www.quantconnect.com/docs/v2/writing-algorithms/importing-data/streaming-data/custom-securities/key-concepts#08-Set-the-Benchmark'>set the benchmark to the subscribed custom data</a> or a constant.
        </p>
        <div class='section-example-container'>
            <pre class='csharp'>SetBenchmark(x => 0);</pre>
            <pre class='python'>self.set_benchmark(lambda x: 0)</pre>
        </div>
";
$supportsIQFeed = true;
$requiresSubscription = false; 
$supportsCashHoldings = true;
$supportsPositionHoldings = true;
include(DOCS_RESOURCES."/brokerages/cli-deployment/deploy-local-algorithms.php");
?>
