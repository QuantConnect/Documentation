<p>Follow these steps to plot the equity curve, benchmark, and drawdown of a live algorithm:</p>

<ol>
    <li>Define some helper methods to get the live algorithm charts.</li>
    <div class="section-example-container">
	    <pre class="python">import pytz

def eastern_time(unix_timestamp): 
    return unix_timestamp.replace(tzinfo=pytz.utc).astimezone(pytz.timezone('US/Eastern')).replace(tzinfo=None)

def series(project_id, chart_name, series_name, start=0, end=int(datetime.now().timestamp()), count=999999999, selector=lambda x: x.y):
    return pd.Series({
        eastern_time(value.time): selector(value)
        for value in api.read_live_chart(project_id, chart_name, start, end, count).chart.series[series_name].values
    })</pre>
	</div>

	<li>Define the project Id.</li>
    <div class="section-example-container">
	    <pre class="python">project_id = 23034953</pre>
	</div>
	<p>The process to get your project Id depends on if you use the <a href='/docs/v2/cloud-platform/projects/getting-started#13-Get-Project-Id'>Cloud Platform</a>, <a href='/docs/v2/local-platform/projects/getting-started#14-Get-Project-Id'>Local Platform</a>, or <a href='/docs/v2/lean-cli/projects/project-management#07-Get-Project-Id'>CLI</a>.</p>
	
    <li>Get the "Equity", "Equity Drawdown", and "Benchmark" time series data.</li>
    <div class="section-example-container">
	    <pre class="python">equity = series(project_id, 'Strategy Equity', 'Equity', selector=lambda x: x.close)
drawdown = series(project_id, 'Drawdown', 'Equity Drawdown')
benchmark = series(project_id, 'Benchmark', 'Benchmark')</pre>
	</div>

    <? include(DOCS_RESOURCES."/qc-api/plot-metadata.php"); ?>
</ol>

<p>The following table shows all the chart series you can plot:</p>

<?include(DOCS_RESOURCES."/qc-api/metadata-table.html");?>
