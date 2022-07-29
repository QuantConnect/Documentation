<p>There are a few common order errors you may experience.</p>

<h4>Why is my order converted to a market on open order?</h4>
<p>
If you place a market order when the market is closed, LEAN automatically converts the order into market on open order. This most commonly happens when you use daily or hourly data, which your algorithm can receive when the market is closed. Your algorithm receives daily bars at midnight and receives the last hourly bar of each day at 4 PM Eastern Time (ET). To avoid LEAN from converting your market order to market on open orders, submit your market orders when the market is open. To check if the market is open, call the <code>IsMarketOpen</code> method.<br></p>

<div class="section-example-container">
<pre class="csharp">if (IsMarketOpen(_symbol))
{
    MarketOrder(symbol, quantity);
}</pre>
<pre class="python">if self.IsMarketOpen(self.symbol):
    self.MarketOrder(self.symbol, quantity)</pre>
</div>

<h4>Why am I seeing the "stale price" warning?</h4>

<?php echo file_get_contents(DOCS_RESOURCES."/order-types/stale-fills.html"); ?>

<h4>Why do I get "Backtest Handled Error: Order Error: id: XXX, Insufficient buying power to complete order" calling the <code>SetHoldings</code> method?</h4>

<p>This error usually occurs when you place a market on open order with daily data. If you place the order with <code>SetHoldings</code> or use <code>CalculateOrderQuantity</code> to determine the order quantity, LEAN calculates the order quantity based on the market close price. If the open price on the following day makes your order more expensive, then you may have insufficient buying power. To avoid issues, use intraday data and place trades when the market is open or <a href='/docs/v2/writing-algorithms/trading-and-orders/position-sizing#05-Buying-Power-Buffer'>adjust your buying power buffer</a>.</p>

<h4>Why can I only place 10,000 orders in a backtest?</h4>

<p>The number of orders you can place in a single backtest depends on the tier of your organization. The following table shows the number of orders you can place on each tier:</p>

<?php echo file_get_contents(DOCS_RESOURCES."/quotas/orders.html"); ?>

<p>To place more orders in a backtest, <a href='/docs/v2/our-platform/organizations/billing#07-Change-Organization-Tiers'>upgrade your organization</a>.<br></p>
