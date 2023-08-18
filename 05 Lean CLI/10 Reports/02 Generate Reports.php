<p>
    Follow these steps to generate a report of a trading algorithm:
</p>

<ol>
    <li>Open a terminal in the <a href='/docs/v2/lean-cli/initialization/organization-workspaces'>organization workspace</a> that contains the project.</li>
    <li>Run <code>lean report</code> to generate a report of the most recent backtest.
<div class="cli section-example-container">
<pre>$ lean report
20210322 20:03:48.718 TRACE:: QuantConnect.Report.Main(): Parsing source files...backtest-data-source-file.json,
20210322 20:03:51.602 TRACE:: QuantConnect.Report.Main(): Instantiating report...
Successfully generated report to './report.html'
</pre>
</div>
        By default, the generated report is saved to <span class="public-file-name">. / report.html</span>, although you can change this by providing a custom path with the <code>--report-destination &lt;path&gt;</code> option.
        To generate a report of a backtest that is not the most recent one, you can use the <code>--backtest-results &lt;path&gt;</code> option to specify the path to the backtest results JSON file to generate a report for it.&nbsp;
    </li>
    <li>Open the generated report in the browser and inspect its results.</li>
</ol>

<p>
    You can also configure the following optional details:
</p>


<table class="qc-table table">
    <thead>
        <tr>
            <th>Detail</th>
            <th>Description</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Strategy name</td>
            <td>This name is displayed in the top-right corner of each page and can be configured using <code>--strategy-name &lt;value&gt;</code>. This value defaults to the name of the project directory.</td>
        </tr>
        <tr>
            <td>Strategy version</td>
            <td>This version is displayed next to the strategy name and can be configured using <code>--strategy-version &lt;value&gt;</code>.</td>
        </tr>
        <tr>
            <td>Strategy description</td>
            <td>This description is displayed underneath the "Strategy Description" header on the first page and can be configured using <code>--strategy-description</code>. This value defaults to the description stored in the <a href="/docs/v2/lean-cli/projects/configuration">project's configuration</a>.</td>
        </tr>
        <tr>
            <td>Live results</td>
            <td>These results are displayed over the backtest results and can be configured using <code>--live-results &lt;path&gt;</code>. The provided path must point to a JSON file containing live results. For example, <code>--live-results "My Project/live/2022-03-17_10-53-12/L-3578882079.json"</code>.</td>
        </tr>
    </tbody>
</table>

<? 
$addHTMLFileInstructions = "add a new HTML file to your local machine. If you add it to your organization workspace, don't name it <span class='public-file-name'>report.html</span> because that's the default name and location of the reports you generate.";
$addCSSFileInstructions = "add a new CSS file to your local machine";
include(DOCS_RESOURCES."/backtesting/results/customize-report.php"); 
?>