<p>Follow these steps to plot the live and OOS backtest equity curves on the same axes.</p>

<ol>
    <li>Read the live "Strategy Equity" chart using the retry helper from the previous step.</li>
    <div class="section-example-container">
        <pre class="csharp">var liveEquityChart = ReadLiveChartWithRetry(projectId, "Strategy Equity");</pre>
        <pre class="python">live_equity_chart = read_chart(project_id, 'Strategy Equity')</pre>
    </div>

    <li>Read the backtest "Strategy Equity" chart by calling the <code class="csharp">ReadBacktestChart</code><code class="python">read_backtest_chart</code> method with the same retry pattern.</li>
    <div class="section-example-container">
        <pre class="csharp">Chart ReadBacktestChartWithRetry(int projectId, string backtestId, string chartName)
{
    for (var attempt = 0; attempt &lt; 10; attempt++)
    {
        var result = api.ReadBacktestChart(projectId, chartName, 0, nowSec, 500, backtestId);
        if (result.Success) return result.Chart;
        Console.WriteLine($"Chart data is loading... (attempt {attempt + 1}/10)");
        Thread.Sleep(10000);
    }
    throw new Exception($"Failed to read backtest {chartName} chart after 10 attempts");
}

var backtestEquityChart = ReadBacktestChartWithRetry(projectId, backtestId, "Strategy Equity");</pre>
        <pre class="python">def read_backtest_chart(project_id, backtest_id, chart_name, start=0, end=int(time()), count=500):
    for attempt in range(10):
        result = api.read_backtest_chart(project_id, chart_name, start, end, count, backtest_id)
        if result.success:
            return result.chart
        print(f"Chart data is loading... (attempt {attempt + 1}/10)")
        sleep(10)
    raise RuntimeError(f"Failed to read backtest {chart_name} chart after 10 attempts")

backtest_equity_chart = read_backtest_chart(project_id, backtest_id, 'Strategy Equity')</pre>
    </div>

    <li>Extract the <code>Equity</code> series from each chart, filtering out points with a null close. Python uses a <code>pandas.Series</code> indexed by timestamp so the two curves can be aligned on the union of their timestamps; C# keeps two lists of <code>Candlestick</code> points and lets Plotly.NET align them on the same x-axis.</li>
    <div class="section-example-container">
        <pre class="csharp">var liveValues = liveEquityChart.Series["Equity"].Values
    .OfType&lt;Candlestick&gt;()
    .Where(v =&gt; v.Close.HasValue)
    .ToList();
var backtestValues = backtestEquityChart.Series["Equity"].Values
    .OfType&lt;Candlestick&gt;()
    .Where(v =&gt; v.Close.HasValue)
    .ToList();</pre>
        <pre class="python">import pandas as pd

def to_naive(t):
    ts = pd.Timestamp(t)
    return ts.tz_convert('UTC').tz_localize(None) if ts.tzinfo else ts

def to_series(chart, series_name='Equity'):
    values = [v for v in chart.series[series_name].values if v.close is not None]
    return pd.Series(
        [v.close for v in values],
        index=pd.DatetimeIndex([to_naive(v.time) for v in values])
    )

live_series = to_series(live_equity_chart)
backtest_series = to_series(backtest_equity_chart)

# Keep every timestamp from both sources; align and forward-fill on the union.
df = pd.concat([live_series.rename('Live'), backtest_series.rename('OOS Backtest')], axis=1).sort_index().ffill()</pre>
    </div>

    <li>Plot both curves on the same axis. Python uses <code>matplotlib</code>; C# uses <a href='/docs/v2/research-environment/charting/plotly-net'>Plotly.NET</a> — load <code>Plotly.NET</code> and <code>Plotly.NET.Interactive</code> from NuGet and alias <code>Plotly.NET.Chart</code> to avoid ambiguity with <code>QuantConnect.Chart</code>.</li>
    <div class="section-example-container">
        <pre class="csharp">#r "nuget: Plotly.NET"
#r "nuget: Plotly.NET.Interactive"
using PlotlyChart = Plotly.NET.Chart;
using Plotly.NET;
using Plotly.NET.Interactive;
using Plotly.NET.LayoutObjects;

var equityChart = PlotlyChart.Combine(new[]
{
    Chart2D.Chart.Line&lt;DateTime, decimal, string&gt;(
        liveValues.Select(v =&gt; v.Time),
        liveValues.Select(v =&gt; v.Close.Value),
        Name: "Live"),
    Chart2D.Chart.Line&lt;DateTime, decimal, string&gt;(
        backtestValues.Select(v =&gt; v.Time),
        backtestValues.Select(v =&gt; v.Close.Value),
        Name: "OOS Backtest")
}).WithTitle("Live vs OOS Backtest Equity");

display(equityChart);</pre>
        <pre class="python">import matplotlib.pyplot as plt

fig, ax = plt.subplots(figsize=(12, 6))
ax.plot(df.index, df['Live'], label='Live')
ax.plot(df.index, df['OOS Backtest'], label='OOS Backtest')
ax.set_title('Live vs OOS Backtest Equity')
ax.set_xlabel('Time')
ax.set_ylabel('Portfolio Value ($)')
ax.legend()
plt.show()</pre>
    </div>
    <img class='docs-image' src="https://cdn.quantconnect.com/i/tu/reconciliation-4.png" alt="Live vs OOS backtest equity curves">

    <li>Score the reconciliation with the annualized returns DTW distance and the Pearson correlation of daily returns. Use <code>tslearn</code>'s <code>dtw</code> with a Sakoe-Chiba band so the algorithm runs in linear time. The <code>tslearn</code> library is Python-only; run this step in a Python research notebook.</li>
    <div class="section-example-container">
        <pre class="python">from tslearn.metrics import dtw as DynamicTimeWarping

returns = df.pct_change().dropna()

# Pearson correlation between live and OOS backtest daily returns (closer to 1 is better).
returns_correlation = returns.corr().iloc[0, 1]

# Raw DTW distance on the returns curves.
raw_dtw = DynamicTimeWarping(
    returns['Live'], returns['OOS Backtest'],
    global_constraint='sakoe_chiba', sakoe_chiba_radius=3
)
# Annualize so the distance is on the scale of yearly percent returns (closer to 0 is better).
annualized_dtw = abs(((1 + (raw_dtw / returns.shape[0])) ** 252) - 1)

print(f"Returns correlation: {returns_correlation:.3f}")
print(f"Annualized returns DTW: {annualized_dtw:.3f}")</pre>
    </div>
</ol>
