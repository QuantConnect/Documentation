<p>Follow these steps to plot the live and OOS backtest equity curves on the same axes.</p>

<ol>
    <li>Read the live "Strategy Equity" chart using the retry helper from the previous step.</li>
    <div class="section-example-container">
        <pre class="python">live_equity_chart = read_chart(project_id, 'Strategy Equity')</pre>
    </div>

    <li>Read the backtest "Strategy Equity" chart by calling the <code class="csharp">ReadBacktestChart</code><code class="python">read_backtest_chart</code> method with the same retry pattern.</li>
    <div class="section-example-container">
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

    <li>Extract the <code>Equity</code> series from each chart into a <code>pandas.Series</code> indexed by timestamp. Preserve every timestamp from both the live and backtest series and strip any timezone info so <code>pandas</code> and <code>plotly</code> can align and render the curves cleanly.</li>
    <div class="section-example-container">
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

    <li>Plot both curves on a single <code>matplotlib</code> axis.</li>
    <div class="section-example-container">
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

    <li>Score the reconciliation with the annualized returns DTW distance and the Pearson correlation of daily returns. Use <code>tslearn</code>'s <code>dtw</code> with a Sakoe-Chiba band so the algorithm runs in linear time.</li>
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
