<p>Follow these steps to plot the equity curve, benchmark, and drawdown of a backtest:</p>

<ol>
    <li>Define some helper methods to get the backtest charts.</li>
    <div class="section-example-container">
	    <pre class="python">import pytz

def eastern_time(unix_timestamp): 
    return unix_timestamp.replace(tzinfo=pytz.utc).astimezone(pytz.timezone('US/Eastern')).replace(tzinfo=None)

def series(
        project_id, backtest_id, chart_name, series_name, start=0, end=int(datetime.now().timestamp()), 
        count=999999999, selector=lambda x: x.y):
    return pd.Series({
        eastern_time(value.time): selector(value) for value in api.read_backtest_chart(
            project_id, chart_name, start, end, count, backtest_id
        ).chart.series[series_name].values
    })</pre>
	</div>

	<li>Define the project Id and backtest Id.</li>
    <div class="section-example-container">
	    <pre class="python">project_id = 23034953
backtest_id = 'ff616bb2cbccf70f61ea431278e57728'</pre>
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
	            <td><a href='/docs/v2/cloud-platform/backtesting/getting-started#07-Get-Backtest-Id'>Get Backtest Id</a></td>
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

    <li>Get the "Equity", "Equity Drawdown", and "Benchmark" time series data.</li>
    <div class="section-example-container">
	    <pre class="python">equity = series(project_id, backtest_id, 'Strategy Equity', 'Equity', selector=lambda x: x.close)
drawdown = series(project_id, backtest_id, 'Drawdown', 'Equity Drawdown')
benchmark = series(project_id, backtest_id, 'Benchmark', 'Benchmark')</pre>
	</div>
	
    <? include(DOCS_RESOURCES."/qc-api/plot-metadata.php"); ?>
</ol>

<p>The following table shows all the chart series you can plot:</p>

<?include(DOCS_RESOURCES."/qc-api/metadata-table.html");?>
