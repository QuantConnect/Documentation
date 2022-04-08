<?php
include(DOCS_RESOURCES."/brokerages/cli-deployment/deploy-local-algorithms.php");


$brokerageDetails = "
<li>Set up IB Key Security via IBKR Mobile. For instructions, see <a href='https://guides.interactivebrokers.com/iphone/log_in/ibkey.htm?tocpath=IB%20Key%20Security%20Protocol%7C_____0' target='_blank' rel='nofollow'>IB Key Security via IBKR Mobile</a> on the IB website.</li>

<li>Go back to the terminal and enter your Interactive Brokers username, account id, and password.
<div class='cli section-example-container'>
<pre>$ lean live \"My Project\"
Username: trader777
Account id: DU1234567
Account password: ****************</pre>
</div>
</li>
";

$dataFeedDetails = "
<li>Enter whether you want to enable delayed market data.
<div class='cli section-example-container'>
<pre>$ lean live \"My Project\"
Enable delayed market data? [y/N]: y</pre>
</div>
This property configures the behavior when your algorithm attempts to subscribe to market data for which you don't have a market data subscription on Interactive Brokers. When enabled, your algorithm continues running using delayed market data. When disabled, live trading will stop and LEAN will shut down.
</li>
";

$supportsIQFeed = true;
$requiresSubscription = true;

$getDeployLocalAlgorithmsText("Interactive Brokers", $brokerageDetails, $dataFeedDetails, $supportsIQFeed, $requiresSubscription);
?>
