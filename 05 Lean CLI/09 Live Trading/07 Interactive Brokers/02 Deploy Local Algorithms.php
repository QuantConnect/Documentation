<?php
include(DOCS_RESOURCES."/brokerages/cli-deployment/deploy-local-algorithms.php");


$brokerageDetails = "
<li>Log in to Interactive Brokers in your browser and go to <span class='menu-name'>User Settings &gt; Security &gt; Secure Login System</a>.</li>

<li>Deselect all options or only select <span class='box-name'>IB Key Security via IBKR Mobile</span> on the Secure Login System page.</li>

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
<li>Follow the steps of configuring Interactive Brokers as brokerage above if you chose paper trading as the brokerage.</li>

<li>Enter whether you want to enable delayed market data.
<div class='cli section-example-container'>
<pre>$ lean live \"My Project\"
Enable delayed market data? [y/N]: y</pre>
</div>
This property configures the behavior when your algorithm attempts to subscribe to market data for which you don't have a market data subscription on Interactive Brokers. When enabled, your algorithm continues running using delayed market data. When disabled, live trading will stop and LEAN will shut down.
</li>
";

$supportsIQFeed = true;

$getDeployLocalAlgorithmsText("Interactive Brokers", $brokerageDetails, $dataFeedDetails, $supportsIQFeed);
?>