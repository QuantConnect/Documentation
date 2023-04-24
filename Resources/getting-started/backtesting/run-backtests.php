<p>To run a backtest, <a href="/docs/v2/cloud-platform/projects/getting-started#02-View-All-Projects">open a project</a> and then click the <?php if ($localPlatform) {?><img class='inline-icon' src='https://cdn.quantconnect.com/i/tu/local-backtest.png'>/<?php } ?><img class='inline-icon' src='https://cdn.quantconnect.com/i/tu/backtest-button.png'> <span class='icon-name'>Backtest</span> icon. If the project successfully builds, "Received backtest <span class='placeholder-text'>backtestName</span> request" displays. If the backtest successfully launches, the IDE displays the <a href='/docs/v2/cloud-platform/backtesting/results'>backtest results page</a> in a new tab. If the backtest fails to launch due to coding errors, the new tab displays the error. 
<?php if ($localPlatform) {
	echo "If you deploy the backtest to QuantConnect Cloud, a";
} 
else {
	echo "A";
}
echo "s the backtest executes, you can refresh or close the IDE without interfering with the backtest because it runs on our cloud servers.";
?></p>
