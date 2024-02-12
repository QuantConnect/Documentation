<?
ob_start();
$extraText = "Enter a time on Sunday to receive the notification.";
include(DOCS_RESOURCES."/brokerages/interactive-brokers/weekly-restarts.php");
$weeklyRestartText = ob_get_clean();

$brokerageDetails = "
<li>Set up IB Key Security via IBKR Mobile. For instructions, see <a href='https://ibkrguides.com/securelogin/sls/ibkrmobile.htm' target='_blank' rel='nofollow'>IB Key Security via IBKR Mobile</a> on the IB website.</li>

<li>Go back to the terminal and enter your Interactive Brokers username, account id, and password.</li>
<div class='cli section-example-container'>
<pre>$ lean cloud live \"My Project\" --push --open
Username: trader777
Account id: DU1234567
Account password: ****************</pre>
</div>

<li>Enter a weekly restart time that's convenient for you.</li>
<div class='cli section-example-container'>
<pre>$ lean cloud live \"My Project\" --push --open
Weekly restart UTC time (hh:mm:ss) [21:00:00]: </pre>
</div>
{$weeklyRestartText}

<li>Enter whether you want to use the <a href='https://www.quantconnect.com/docs/v2/cloud-platform/live-trading/data-providers/interactive-brokers'>price data from Interactive Brokers</a> instead of the data from QuantConnect. Enabling this feature requires you to have active Interactive Brokers market data subscriptions for all data required by your algorithm.</li>
<div class='cli section-example-container'>
<pre>$ lean cloud live \"My Project\" --push --open
Do you want to use the Interactive Brokers price data feed instead of the QuantConnect price data feed? (yes/no): y</pre>
</div>
";
$brokerageName="Interactive Brokers";
$isSupported=true;
$supportsCashHoldings=false;
$supportsPositionHoldings=false;
include(DOCS_RESOURCES."/brokerages/cli-deployment/deploy-cloud-algorithms.php");
?>
