<p>The <?=$pageName?> results page displays the closed trades of your algorithm and you can <? echo $cloudPlatform ? "download them to your local machine" : "view them on your local machine";?>. You can set how orders are combined to define a trade. For more information, see <a href="/docs/v2/writing-algorithms/trading-and-orders/trade-statistics">Trade Statistics</a></p>
        
<h4>View in the GUI</h4>
<p>To see the trades that your algorithm created, open the <?=$pageName?> results page and then click the <span class='tab-name'>Trades</span> tab. If there are more than 10 trades, use the pagination tools at the bottom of the Trades Summary table to see all of the orders. Click on an individual trade in the Trades Summary table to reveal the <a href='/docs/v2/writing-algorithms/trading-and-orders/order-events'>order fills</a> of the trade.</p>

<p>The timestamps in the Trades Summary table are based in Eastern Time (ET).</p>

<h4>Access the Trade Summary CSV</h4>
<p>To view the trades data in CSV format, open the <?=$pageName?> results page, click the <span class='tab-name'>Trades</span> tab, and then click <span class='button-name'>Download Trades</span>. The content of the CSV file is the content displayed in the Trades Summary table when the table rows are collapsed. The timestamps in the CSV file are based in Coordinated Universal Time (UTC). <? if ($localPlatform) { ?>If you download the trade summary CSV for a local backtest, the file is stored in <span class='public-file-name'><a href='/docs/v2/local-platform/development-environment/organization-workspaces'>&lt;organizationWorkspace&gt;</a> / &lt;projectName&gt; / backtests / &lt;unixTimestamp&gt; / trades.csv</span>.<? } ?></p>

<? if ($pageName == "backtest") { ?>
<h4>Access the Trades JSON</h4>
<?     if ($cloudPlatform) { ?>
<p>To view all of the content in the Trades Summary table, see <a href='/docs/v2/cloud-platform/backtesting/results#16-Download-Results'>Download Results</a>.</p>
<?     } ?>

<?     if ($localPlatform) { ?>
<p>To view all of the content in the Trades Summary table, open the <span class='public-file-name'><a href='/docs/v2/local-platform/development-environment/organization-workspaces'>&lt;organizationWorkspace&gt;</a> / &lt;projectName&gt; / backtests / &lt;unixTimestamp&gt; / &lt;algorithmId&gt;.json</span> file and search for the <code>closedTrades</code> key.<p>
<?     } ?>

<? } ?>
<p>