<p>The <?=$pageName?> results page displays the orders of your algorithm and you can <? echo $cloudPlatform ? "download them to your local machine" : "view them on your local machine";?>.</p>
        
<h4>View in the GUI</h4>
<p>To see the orders that your algorithm created, open the <?=$pageName?> results page and then click the <span class='tab-name'>Orders</span> tab. If there are more than 10 orders, use the pagination tools at the bottom of the Orders Summary table to see all of the orders. Click on an individual order in the Orders Summary table to reveal additional information regarding the following topics:</p>

<ul>
    <li>Submissions<br></li>
    <li>Fills</li>
    <li>Partial fills</li>
    <li>Updates</li>
    <li>Cancellations</li>
    <li>Option contract exercises and expiration</li>
</ul>
        
<p>The timestamps in the Order Summary table are based in Eastern Time (ET).</p>

<h4>Access the Order Summary CSV</h4>
<p>To view the orders data in CSV format, open the <?=$pageName?> results page, click the <span class='tab-name'>Orders</span> tab, and then click <span class='button-name'>Download Orders</span>. The content of the CSV file is the content displayed in the Orders Summary table when the table rows are collapsed. The timestamps in the CSV file are based in Coordinated Universal Time (UTC). <? if ($localPlatform) { ?>If you download the order summary CSV for a local backtest, the file is stored in <span class='public-file-name'>Project / backtests / &lt;unixTimestamp&gt; / orders.csv</span>.<? } ?></p>

<? if ($pageName == "backtest") { ?>
<h4>Access the Orders JSON</h4>
<?     if ($cloudPlatform) { ?>
<p>To view all of the content in the Orders Summary table, see <a href='/docs/v2/cloud-platform/backtesting/results#15-Download-Results'>Download Results</a>.</p>
<?     } ?>

<?     if ($localPlatform) { ?>
<p>To view all of the content in the Orders Summary table, see <a href='/docs/v2/local-platform/backtesting/results#15-View-Local-Result-Files'>View Local Result Files</a> or <a href='/docs/v2/local-platform/backtesting/results#16-Download-Cloud-Result-Files'>Download Cloud Result Files</a>.</p>

<h4>Access the Order Events JSON</h4>
<p>To view all of the <a href='/docs/v2/writing-algorithms/trading-and-orders/order-events'>order events</a> for a local backtest, open one of the following files:<p>

<ul>
    <li>The <span class='public-file-name'>Project / backtests / &lt;unixTimestamp&gt; / &lt;algorithmId&gt;-order-events.json</span> file from your <a href='/docs/v2/local-platform/backtesting/results#15-View-Local-Result-Files'>local result files</a> in Local Platform</li>
    <li>The <span class='public-file-name'>&lt;projectName&gt; / backtests / &lt;unixTimestamp&gt; / &lt;algorithmId&gt;-log.txt</span> file from your <a href='/docs/v2/local-platform/development-environment/organization-workspaces'>organization workspace</a></li>
</ul>
<?     } ?>

<? } ?>
