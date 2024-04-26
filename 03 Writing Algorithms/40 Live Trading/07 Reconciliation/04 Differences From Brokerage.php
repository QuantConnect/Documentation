<p>The brokerage that your algorithm uses can cause differences between backtesting and live trading performance.</p>

<h4>Portfolio Allocations on Small Accounts</h4>
<p>If you trade a small portfolio, it's difficult to achieve accurate portfolio allocations because shares are usually sold in whole numbers. For instance, you likely can't allocate exactly 10% of your portfolio to a security. You can use fractional shares to achieve accurate portfolio allocations, but not all brokerages support fractional shares. To get the closest results when backtesting and live trading over the same period, ensure both algorithms have the same starting cash balance.</p>


<h4>Different Backtest Parameters</h4>
<p>If you don't start your backtest and live deployment on the same date with the same holdings, deviations can occur between backtesting and live trading. To avoid issues, ensure your backtest parameters are the same as your live deployment.</p>

<h4>Non-deterministic State From Algorithm Restarts</h4>
<p>If you stop and redeploy your live trading algorithm, it needs to restart in a stateful way or else deviations can occur between backtesting and live trading. To avoid issues, redeploy your algorithm in a stateful way using the <code class="csharp">SetWarmUp</code><code class="python">set_warm_up</code> and <code class="csharp">History</code><code class="python">history</code> methods. Furthermore, use the Object Store to save state information between your live trading deployments.</p>


<h4>Existing Portfolio Securities</h4>
<p>If you deploy your algorithm to live trading with a brokerage account that has existing holdings, your live trading equity curve reflects your existing positions, but the backtesting curve won't. Therefore, if you have existing positions in your brokerage account when you deploy your algorithm to live trading, deviations will occur between backtesting and live trading. To avoid issues, deploy your algorithm to live trading using a separate brokerage account or subaccount that does not have existing positions.<br></p>

<h4>Brokerage Limitations</h4>
<p>We provide brokerage models that support specific order types and model your buying power. In backtesting, we simulate your orders&nbsp; with the brokerage model you select. In live trading, we send your orders to your brokerage for execution. If the brokerage model that you use in backtesting is not the same brokerage that you use in live trading, deviations may occur between backtesting and live trading. The deviations can occur if your live brokerage doesn't support the order types that you use or if the backtesting brokerage models your buying power with a different methodology than the real brokerage. To avoid brokerage model issues, set the <a href="/docs/v2/writing-algorithms/reality-modeling/brokerages/key-concepts">brokerage model</a> in your backtest to the same brokerage that you use in live trading.<br></p>