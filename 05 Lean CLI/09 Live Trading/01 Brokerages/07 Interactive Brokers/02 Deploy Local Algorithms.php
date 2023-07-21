<?
$brokerageName = "Interactive Brokers";
$dataFeedName = "";
$isBrokerage = true;

ob_start();
$extraText = "Enter a time on Sunday to receive the notification.";
include(DOCS_RESOURCES."/brokerages/interactive-brokers/weekly-restarts.php");
$weeklyRestartText = ob_get_clean();

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

<li>Enter </li>
<div class='cli section-example-container'>
<pre>$ lean live \"My Project\"
Weekly restart UTC time (hh:mm:ss) [21:00:00]: </pre>
</div>
{$weeklyRestartText}
</li>
";

$dataFeedDetails = "
<li>Enter whether you want to enable delayed market data.
<div class='cli section-example-container'>
<pre>$ lean live \"My Project\"
Enable delayed market data? [yes/no]: </pre>
</div>
This property configures the behavior when your algorithm attempts to subscribe to market data for which you don't have a market data subscription on Interactive Brokers. When enabled, your algorithm continues running using delayed market data. When disabled, live trading will stop and LEAN will shut down.
</li>
";

$supportsIQFeed = true;
$requiresSubscription = true;
include(DOCS_RESOURCES."/brokerages/cli-deployment/deploy-local-algorithms.php");
?>
