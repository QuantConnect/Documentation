<p>Follow these steps to plot the equity curve, benchmark, and drawdown of a backtest:</p>

<ol>
    <li>Get the backtest instance.</li>
    <div class="section-example-container">
	    <pre class="python">backtest = api.read_backtest(project_id, backtest_id)</pre>
	</div>
	<p>The following table provides links to documentation that explains how to get the project Id and backtest Id, depending on the platform you use:</p>

	<table class="qc-table table">
	    <thead>
	        <tr>
	            <th>Platform</th>
	            <th>Project Id</th>
	            <th>Backtest Id</th>
	        </tr>
	    </thead>
	    <tbody>
	        <tr>
	            <td>Cloud Platform</td>
	            <td><a href='/docs/v2/cloud-platform/projects/getting-started#13-Get-Project-Id'>Get Project Id</a></td>
	            <td><a href='/docs/v2/cloud-platform/backtesting/getting-started#06-Get-Backtest-Id'>Get Backtest Id</a></td>
	        </tr>
	        <tr>
	            <td>Local Platform</td>
	            <td><a href='/docs/v2/local-platform/projects/getting-started#14-Get-Project-Id'>Get Project Id</a></td>
	            <td><a href='/docs/v2/local-platform/backtesting/getting-started#07-Get-Backtest-Id'>Get Backtest Id</a></td>
	        </tr>
	        <tr>
	            <td>CLI</td>
	            <td><a href='/docs/v2/lean-cli/projects/project-management#07-Get-Project-Id'>Get Project Id</a></td>
	            <td><a href='/docs/v2/lean-cli/backtesting/deployment#05-Get-Backtest-Id'>Get Backtest Id</a></td>
	        </tr>
	    </tbody>
	</table>
	
    <?
    $isLive = false;
    include(DOCS_RESOURCES."/qc-api/plot-metadata.php");
    ?>
</ol>

<p>The following table shows all the chart series you can plot:</p>

<?include(DOCS_RESOURCES."/qc-api/metadata-table.html");?>
