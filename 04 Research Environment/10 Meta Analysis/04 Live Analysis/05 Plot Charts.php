<p>Follow these steps to plot the equity curve, benchmark, and drawdown of a live algorithm:</p>

<ol>
    <li>Define the project Id and read the "Strategy Equity", "Drawdown", and "Benchmark" charts.</li>
    <div class="section-example-container">
	    <pre class="python">from time import sleep, time

project_id = 23034953

def read_chart(project_id, chart_name, start=0, end=int(time()), count=500):
    # Retry up to 10 times if the chart data is still loading
    for attempt in range(10):
        result = api.read_live_chart(project_id, chart_name, start, end, count)
        if result.status == 'loading':
            print(f"Chart data is loading... (attempt {attempt + 1}/10)")
            sleep(10)
            continue
        break
    return result.chart

strategy_equity = read_chart(project_id, 'Strategy Equity')
drawdown_chart = read_chart(project_id, 'Drawdown')
benchmark_chart = read_chart(project_id, 'Benchmark')</pre>
	</div>
	<p>The process to get your project Id depends on if you use the <a href='/docs/v2/cloud-platform/projects/getting-started#13-Get-Project-Id'>Cloud Platform</a>, <a href='/docs/v2/local-platform/projects/getting-started#14-Get-Project-Id'>Local Platform</a>, or <a href='/docs/v2/lean-cli/projects/project-management#07-Get-Project-Id'>CLI</a>.</p>

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
