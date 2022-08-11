<p>The data that your algorithm uses can cause differences between backtesting and live trading performance.</p>

<h4>Look-Ahead Bias</h4>
<p>The <a href="/docs/v2/writing-algorithms/key-concepts/time-modeling/timeslices">Time Frontier</a> minimizes the risk of look-ahead bias in backtests, but it does not completely eliminate the risk of look-ahead bias. For instance, if you use a <a href="/docs/v2/writing-algorithms/importing-data/key-concepts">custom dataset</a> that contains look-ahead bias, your algorithm's live and backtest equity curves may deviate. To avoid look-ahead bias with custom datasets, set a <code>Period</code> on your custom data points so that your algorithm receives the data points after the <code>Time</code> + <code>Period</code>.</p>

<h4>Discrete Time Steps</h4>
<p>In backtests, we inject data into your algorithm at predictable times, according to the data resolution. In live trading, we inject data into your algorithm when new data is available. Therefore, if your algorithm has a condition with a specific time (i.e. time is 9:30:15), the condition may work in backtests but it will always fail in live trading since live data has microsecond precision. To avoid issues, either use a time range in your condition (i.e. 9:30:10 &lt; time &lt; 9:30:20), use a rounded time, or use a Scheduled Event.</p>

<h4>Custom Data Emission Times</h4>
<p>Custom data is often timestamped to midnight, but the data point may not be available in reality until several days after that point. If your custom dataset is prone to this delay, your backtest may not fetch the same data at the same time or frequency that your live trading algorithm receives the data, leading to deviations between backtesting and live trading. To avoid issues, ensure the timestamps of your custom dataset are the times when the data points would be available in reality.</p>

<p>In backtesting, LEAN and custom data are perfectly synchonized. In live trading, daily and hourly data from a custom data source are not because of the frequency that LEAN checks the data source depends on the <code>resolution</code> argument. The following table shows the polling frequency of each resolution:</p>
<?php include(DOCS_RESOURCES."/datasets/live-dataset-polling-frequency-table.html"); ?>

<h4>Split Adjustment of Indicators</h4>
<p>Backtests use adjusted price data by default. Therefore, if you don't change the <a href="/docs/v2/our-platform/datasets/misconceptions#05-Data-Normalization">data normalization mode</a>, the indicators in your backtests are updated with adjusted price data. In contrast, if a split or dividend occurs in live trading, your indicators will temporarily contain price data from before the corporate event and price data from after the corporate event. If this occurs, your indicators will produce different signals in your backtests compared to your live trading deployment. To avoid issues, <a href="/docs/v2/writing-algorithms/indicators/key-concepts#09-Reset-Indicators">reset and warm up your indicators</a> when your algorithm receives a corporate event.</p>

<h4>Tick Slice Sizes</h4>
<p>In backtesting, we collect ticks into slices that span 1 millisecond before injecting them into your algorithm. In live trading, we collect ticks into slices that span up to 70 milliseconds before injecting them into your algorithm. This difference in slice sizes can cause deviations between your algorithm's live and OOS backtest equity curves. To avoid issues, ensure your strategy logic is compatible with both slice sizes.</p>