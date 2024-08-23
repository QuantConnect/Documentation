<p>There are a few common order errors you may experience.</p>

<h4>Why is my order converted to a market on open order?</h4>
<p>
If you place a market order when the market is closed, LEAN automatically converts the order into market on open order. This most commonly happens when you use daily or hourly data, which your algorithm can receive when the market is closed. With the <code class="csharp">DailyPreciseEndTime</code><code class="python">daily_precise_end_time</code> <a href='/docs/v2/writing-algorithms/initialization#10-Set-Algorithm-Settings'>setting</a> enabled, your algorithm receives daily bars and the last hourly bar of each day at 4 PM Eastern Time (ET). To avoid LEAN from converting your market order to market on open orders, submit your market orders when the market is open. To check if the market is open, call the <code class="csharp">IsMarketOpen</code><code class="python">is_market_open</code> method.<br></p>

<div class="section-example-container">
<pre class="csharp">// Check market status before placing an order to avoid unintended market-on-open trades 
if (IsMarketOpen(_symbol))
{
    MarketOrder(symbol, quantity);
}</pre>
<pre class="python"># Check market status before placing an order to avoid unintended market-on-open trades.
if self.is_market_open(self._symbol):
    self.market_order(self._symbol, quantity)</pre>
</div>

<h4>Why am I seeing the "stale price" warning?</h4>

<? echo file_get_contents(DOCS_RESOURCES."/order-types/stale-fills.html"); ?>

<h4>Why do I get "Backtest Handled Error: The security with symbol '/ES' is marked as non-tradable"?</h4>

<p>This error occurs when you place an order for a continuous Futures contract, which isn't a tradable security. To fix the issue, place the order for a specific Futures contract. To access the currently selected contract in the continuous contract series, use the <code class="csharp">Mapped</code><code class="python">mapped</code> property of the <code>Future</code> object.</p>

<h4>Why do I get "Backtest Handled Error: Order Error: ids: [1], Insufficient buying power to complete orders" from a Crypto order?</h4>
<? echo file_get_contents(DOCS_RESOURCES."/order-types/crypto-insufficient-buying-power.html"); ?>
