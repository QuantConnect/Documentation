<?
$brokerageName = "QuantConnect Paper Trading";
$dataFeedName = "Databento";
$isBrokerage = false;
$brokerageDetails = "";
$dataFeedDetails = "
    <li>Enter your Databento API key.
<div class='cli section-example-container'>
<pre>$ lean live \"My Project\"
Your Databento.com API Key:</pre>
</div>

<p>To get your API key, see the <a rel='nofollow' target='_blank' href='https://databento.com/portal/keys'>API Keys page</a> on the Databento website.</p>
    </li>
";
$supportsIQFeed = false;
$requiresSubscription = false;
include(DOCS_RESOURCES."/brokerages/cli-deployment/deploy-local-algorithms.php");
?>
