<p>The <?=$pageName?> results page displays the orders of your algorithm and you can <? echo $cloudPlatform ? "download them to your local machine" : "view them on your local machine";?>.</p>
        
<h4>View in the GUI</h4>
<p>To see the orders that your algorithm created, open the <?=$pageName?> results page and then click the <span class='tab-name'>Orders</span> tab. If there are more than 10 orders, use the pagination tools at the bottom of the Orders Summary table to see all of the orders. Click on an individual order in the Orders Summary table to reveal all of the <a href='/docs/v2/writing-algorithms/trading-and-orders/order-events'>order events</a>, which include:</p>

<ul>
    <li>Submissions</li>
    <li>Fills</li>
    <li>Partial fills</li>
    <li>Updates</li>
    <li>Cancellations</li>
    <li>Option contract exercises and expiration</li>
</ul>
        
<p>The timestamps in the Order Summary table are based in Eastern Time (ET).</p>

<h4>Access the Order Summary CSV</h4>
<p>To view the orders data in CSV format, open the <?=$pageName?> results page, click the <span class='tab-name'>Orders</span> tab, and then click <span class='button-name'>Download Orders</span>. The content of the CSV file is the content displayed in the Orders Summary table when the table rows are collapsed. The timestamps in the CSV file are based in Coordinated Universal Time (UTC). <? if ($localPlatform) { ?>If you download the order summary CSV for a local backtest, the file is stored in <span class='public-file-name'><a href='/docs/v2/local-platform/development-environment/organization-workspaces'>&lt;organizationWorkspace&gt;</a> / &lt;projectName&gt; / backtests / &lt;unixTimestamp&gt; / orders.csv</span>.<? } ?></p>

<? if ($pageName == "backtest") { ?>
<h4>Access the Orders JSON</h4>
<?     if ($cloudPlatform) { ?>
<p>To view all of the content in the Orders Summary table, see <a href='/docs/v2/cloud-platform/backtesting/results#15-Download-Results'>Download Results</a>.</p>
<?     } ?>

<?     if ($localPlatform) { ?>
<p>To view all of the content in the Orders Summary table, open the <span class='public-file-name'><a href='/docs/v2/local-platform/development-environment/organization-workspaces'>&lt;organizationWorkspace&gt;</a> / &lt;projectName&gt; / backtests / &lt;unixTimestamp&gt; / &lt;algorithmId&gt;.json</span> file and search for the <code>Orders</code> key.<p>

<h4>Access the Order Events JSON</h4>
<p>To view all of the <a href='/docs/v2/writing-algorithms/trading-and-orders/order-events'>order events</a> for a local backtest, open the <span class='public-file-name'><a href='/docs/v2/local-platform/development-environment/organization-workspaces'>&lt;organizationWorkspace&gt;</a> / &lt;projectName&gt; / backtests / &lt;unixTimestamp&gt; / &lt;algorithmId&gt;-order-events.json</span> file.<p>

<?     } ?>

<? } ?>

<h4>Access in Jupyter Notebooks</h4>
<p>To programmatically analyze orders, call the 
<? if ($pageName == "backtest") { ?>
    <a href='/docs/v2/research-environment/meta-analysis/backtest-analysis#03-Plot-Order-Fills'><span class='csharp'>ReadBacktestOrders</span><span class='python'>read_backtest_orders</span></a> method or the <a href='/docs/v2/cloud-platform/api-reference/backtest-management/read-backtest/orders'>/backtests/orders/read</a> endpoint.
<? } else { ?>
    <a href='/docs/v2/research-environment/meta-analysis/live-analysis#04-Plot-Order-Fills'><span class='csharp'>ReadBacktestOrders</span><span class='python'>read_live_orders</span></a> method or the <a href='/docs/v2/cloud-platform/api-reference/live-management/read-live-algorithm/orders'>/live/orders/read</a> endpoint.
<? } ?>
<? if ($localPlatform) { ?> This method and endpoint only work if you deploy the algorithm in QC Cloud.<? } ?>
<p>

        
<p>To see and analyze orders programmatically, call <code class="csharp">api.<? if ($pageName == "backtest") { ?>ReadBacktestOrders<? } else { ?>ReadLiveOrders<? } ?></code><code class="python">api.<? if ($pageName == "backtest") { ?>read_backtest_orders<? } else { ?>read_live_orders<? } ?></code> access the order in a jupyter notebook via the API. A List of <code>ApiOrderResponse</code> will be returned.</p>
<table class="table qc-table">
    <thead>
        <tr>
            <th style="width: 20%">Argument</th>
            <th style="width: 20%">Type</th>
            <th style="width: 40%">Description</th>
            <th style="width: 20%">Default</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>ProjectId</td>
            <td>int</td>
            <td>Id of the project from which to read the orders</td>
            <td></td>
        </tr>
<? if ($pageName == "backtest") { ?>
        <tr>
            <td>backtestId</td>
            <td>string</td>
            <td>Id of the backtest from which to read the orders</td>
            <td></td>
        </tr>
<? } ?>
        <tr>
            <td>start</td>
            <td>int</td>
            <td>Starting index of the orders to be fetched. Required if end &gt; 100</td>
            <td>0</td>
        </tr>
        <tr>
            <td>end</td>
            <td>int</td>
            <td>Last index of the orders to be fetched. Note that end - start must be &le; 100</td>
            <td>100</td>
        </tr>
    </tbody>
</table>
<div class="section-example-container">
<? if ($pageName == "backtest") { ?>
    <pre class="csharp">var orders = api.ReadBacktestOrders(projectId, "&lt;backtestId&gt;", 0, 100);</pre>
    <pre class="python">orders = api.read_backtest_orders(project_id, "&lt;backtest_id&gt;", 0, 100)</pre>
<? } else { ?>
    <pre class="csharp">var orders = api.ReadLiveOrders(projectId, 0, 100);</pre>
    <pre class="python">orders = api.read_live_orders(project_id, 0, 100)</pre>
<? } ?>
</div>
<!--- full links for URL checker --->
<p>Refer to the <? if ($pageName == "backtest") { ?><a href="/docs/v2/cloud-platform/api-reference/backtest-management/read-backtest/orders">API Reference page</a><? } else { ?><a href="/docs/v2/research-environment/meta-analysis/live-analysis">meta analysis page</a> and the <a href="/docs/v2/cloud-platform/api-reference/live-management/read-live-algorithm/orders">API Reference page</a><? } ?> for details.</p>
