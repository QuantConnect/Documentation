<p>Follow these steps to plot the equity curve, benchmark, and drawdown of a backtest:</p>

<ol>
    <li>Define the project Id, backtest Id, and read the "Strategy Equity", "Drawdown", and "Benchmark" charts.</li>
    <div class="section-example-container">
	    <pre class="python">from time import time

project_id = 23034953
backtest_id = 'ff616bb2cbccf70f61ea431278e57728'

def read_chart(project_id, backtest_id, chart_name, start=0, end=int(time()), count=500):
    return api.read_backtest_chart(
        project_id, chart_name, start, end, count, backtest_id
    ).chart

strategy_equity = read_chart(project_id, backtest_id, 'Strategy Equity')
drawdown_chart = read_chart(project_id, backtest_id, 'Drawdown')
benchmark_chart = read_chart(project_id, backtest_id, 'Benchmark')</pre>
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

    <li>Extract the series and create a <code>pandas.DataFrame</code>.</li>
    <div class="section-example-container">
        <pre class="python">def to_series(chart, series_name, selector=lambda x: x.y):
    return pd.Series({v.time: selector(v) for v in chart.series[series_name].values})

df = pd.DataFrame({
    "Equity": to_series(strategy_equity, 'Equity', selector=lambda x: x.close),
    "Return": to_series(strategy_equity, 'Return'),
    "Drawdown": to_series(drawdown_chart, 'Equity Drawdown'),
    "Benchmark": to_series(benchmark_chart, 'Benchmark')
}).ffill()
df.index = df.index.tz_localize('UTC').tz_convert('US/Eastern').tz_localize(None)</pre>
    </div>

    <li>Plot the performance chart.</li>
    <div class="section-example-container">
        <pre class="python"># Create subplots to plot series on same/different plots
fig, ax = plt.subplots(3, 1, figsize=(12, 16), sharex=True, gridspec_kw={'height_ratios': [2, 1, 1]})

# Plot the equity curve
ax[0].plot(df.index, df["Equity"])
ax[0].set_title("Strategy Equity Curve")
ax[0].set_ylabel("Portfolio Value ($)")

# Plot the benchmark on the same plot, scale by using another y-axis
ax2 = ax[0].twinx()
ax2.plot(df.index, df["Benchmark"], color="grey")
ax2.set_ylabel("Benchmark Price ($)", color="grey")

# Plot the daily returns
ax[1].plot(df.index, df["Return"], color="blue")
ax[1].set_title("Daily Return")
ax[1].set_ylabel("%")

# Plot the drawdown on another plot
ax[2].plot(df.index, df["Drawdown"], color="red")
ax[2].set_title("Drawdown")
ax[2].set_xlabel("Time")
ax[2].set_ylabel("%");</pre>
    </div>
    <img class='docs-image' src="https://cdn.quantconnect.com/i/tu/api-result-plot.png" alt="api-equity-curve">
</ol>

<p>The following table shows all the chart series you can plot:</p>

<?include(DOCS_RESOURCES."/qc-api/metadata-table.html");?>
