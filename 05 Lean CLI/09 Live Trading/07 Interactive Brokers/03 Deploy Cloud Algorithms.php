<?php
include(DOCS_RESOURCES."/brokerages/cli-deployment/deploy-cloud-algorithms.php");

$brokerageDetails = "
<li>Log in to Interactive Brokers in your browser and go to User Settings &gt; Security &gt; Secure Login System.</li>

<li>Deselect all options or only select 'IB Key Security via IBKR Mobile' on the Secure Login System page.</li>

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