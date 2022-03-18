<?php
include(DOCS_RESOURCES."/brokerages/cli-deployment/deploy-cloud-algorithms.php");

$brokerageDetails = "
<li>Set up IB Key Security via IBKR Mobile. For instructions, see <a href='https://guides.interactivebrokers.com/iphone/log_in/ibkey.htm?tocpath=IB%20Key%20Security%20Protocol%7C_____0' target='_blank' rel='nofollow'>IB Key Security via IBKR Mobile</a> on the IB website.</li>

<li>Go back to the terminal and enter your Interactive Brokers username, account id, and password.
<div class='cli section-example-container'>
<pre>$ lean cloud live \"My Project\" --push --open
Username: trader777
Account id: DU1234567
Account password: ****************</pre>
</div>
</li>

<li>Enter whether you want to use the price data feed from Interactive Brokers instead of the one from QuantConnect. Enabling this feature requires you to have active Interactive Brokers market data subscriptions for all data required by your algorithm.
<div class='cli section-example-container'>
<pre>$ lean cloud live \"My Project\" --push --open
Do you want to use the Interactive Brokers price data feed instead of the QuantConnect price data feed? [y/N]: y</pre>
</div>
</li>
";

$getDeployCloudAlgorithmsText("Interactive Brokers", true, $brokerageDetails);
?>
