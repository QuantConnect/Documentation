<li>Get the "Strategy Equity" <code>Chart</code> instance.</li>
<div class="section-example-container">
    <pre class="python">chart = <?=$isLive ? "results" : "backtest"?>.Charts["Strategy Equity"]</pre>
</div>

<li>Get values from the "Equity" <code>Series</code> from the chart.</li>
<div class="section-example-container">
    <pre class="python">equity = chart.Series["Equity"].Values</pre>
</div>

<li>Create a <code>pandas.DataFrame</code> from the series values.</li>
<div class="section-example-container">
    <pre class="python">equity_df = pd.Series({datetime.fromtimestamp(value.x): value.y for value in equity})</pre>
</div>

<li>Plot the equity curve.</li>
<div class="section-example-container">
    <pre class="python">equity_df.plot(figsize=(12, 8), title="Strategy Equity Curve", xlabel="time", ylabel="portfolio value ($)")</pre>
</div>
<img src="https://cdn.quantconnect.com/i/tu/api-equity-curve.png" alt="api-equity-curve">