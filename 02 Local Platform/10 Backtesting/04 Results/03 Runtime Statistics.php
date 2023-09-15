<p>The banner at the top of the backtest results page displays the runtime statistics of your backtest.</p>

<img class='docs-image' src="https://cdn.quantconnect.com/i/tu/run-time-statistics-2023.png" alt="Backtest runtime statistics">

<? include(DOCS_RESOURCES."/algorithm-results/runtime-statistics-table.php"); ?>

<p>To view the runtime statistics data in JSON format, open the <span class='public-file-name'><a href='/docs/v2/local-platform/development-environment/organization-workspaces'>&lt;organizationWorkspace&gt;</a> / &lt;projectName&gt; / backtests / &lt;unixTimestamp&gt; / &lt;algorithmId&gt;.json</span> file and search for the <code>RuntimeStatistics</code> key.</p>

<p>To add a custom runtime statistic, see <a href='/docs/v2/writing-algorithms/statistics/runtime-statistics#03-Add-Statistics'>Add Statistics</a>.</p>
