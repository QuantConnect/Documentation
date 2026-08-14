<p>
  The <code class="csharp">OnData</code><code class="python">on_data</code> method runs on every time step, so any cost inside it is multiplied by the number of time steps in the backtest.
  Work that does not need to run that often belongs somewhere else.
</p>

<h4>Move Periodic Work to Scheduled Events</h4>

<p>
  A strategy that rebalances monthly does not need to evaluate its rebalancing logic on every minute bar.
  Put that logic in a <a href="/docs/v2/writing-algorithms/scheduled-events">Scheduled Event</a> and leave the data event handler to the work that genuinely reacts to each bar.
</p>

<div class="section-example-container">
    <pre class="csharp">// Run the rebalancing logic once a month instead of on every data event.
Schedule.On(DateRules.MonthStart(_symbol), TimeRules.AfterMarketOpen(_symbol, 30), Rebalance);</pre>
    <pre class="python"># Run the rebalancing logic once a month instead of on every data event.
self.schedule.on(self.date_rules.month_start(self._symbol), self.time_rules.after_market_open(self._symbol, 30), self._rebalance)</pre>
</div>

<h4>Schedule One Event Per Market, Not One Per Security</h4>

<p>
  The <code class="csharp">Symbol</code><code class="python">Symbol</code> you pass to a date rule or time rule only selects the trading calendar the rule follows.
  Securities that share a market share that calendar, so every US Equity fires at the same moment.
  Schedule one event against any symbol in the market and loop over your securities inside the handler.
  Scheduling one event per security multiplies the <code>Schedule</code> series by the size of your universe and gains you nothing.
</p>

<div class="section-example-container">
    <pre class="csharp">// One event serves every US Equity because they all follow the same market hours.
Schedule.On(DateRules.EveryDay(_spy), TimeRules.BeforeMarketClose(_spy, 10), Rebalance);</pre>
    <pre class="python"># One event serves every US Equity because they all follow the same market hours.
self.schedule.on(self.date_rules.every_day(self._spy), self.time_rules.before_market_close(self._spy, 10), self._rebalance)</pre>
</div>

<p>
  Add a second event only for securities on a different calendar, such as Futures, Crypto, or a foreign exchange.
</p>

<h4>Consolidate Only to a Coarser Period</h4>

<p>
  A <a href="/docs/v2/writing-algorithms/consolidating-data/getting-started">consolidator</a> that produces bars at the resolution you already subscribed to rebuilds a bar LEAN just gave you.
  It costs an update for every bar of every security and returns data you already have.
  Read the bar from the data event handler instead.
  Consolidators earn their cost when the period you want is coarser than the subscription, such as minute data consolidated into hourly or daily bars.
  A large <code>Consolidators</code> series on the Performance chart often points at this mistake.
</p>

<p>
  A consolidator builds the open, high, low, close, and volume of the aggregated bar, so it only pays for itself when you use more than the close.
  When your logic reads the closing value alone, schedule an event at the end of the period and read the price there.
  The Scheduled Event costs one call per period, while the consolidator costs an update for every bar in the period.
</p>

<div class="section-example-container">
    <pre class="csharp">// Read the closing price once a day instead of consolidating minute bars into daily bars.
Schedule.On(DateRules.EveryDay(_spy), TimeRules.BeforeMarketClose(_spy, 1), () => { var close = Securities[_spy].Price; });</pre>
    <pre class="python"># Read the closing price once a day instead of consolidating minute bars into daily bars.
self.schedule.on(self.date_rules.every_day(self._spy), self.time_rules.before_market_close(self._spy, 1), lambda: self._record(self.securities[self._spy].price))</pre>
</div>

<h4>Read the Greeks Instead of Recomputing Them</h4>

<p>
  LEAN evaluates the Option price model lazily and caches the result on the contract.
  Read the Greeks from the Option chain in the current slice.
  Calling the <code class="csharp">EvaluatePriceModel</code><code class="python">evaluate_price_model</code> method yourself repeats work LEAN already did and can dominate the runtime of an Options strategy.
</p>

<div class="section-example-container">
    <pre class="csharp">// Read the cached Greeks from the chain instead of re-evaluating the price model.
foreach (var contract in chain) { var delta = contract.Greeks.Delta; }</pre>
    <pre class="python"># Read the cached Greeks from the chain instead of re-evaluating the price model.
for contract in chain:
    delta = contract.greeks.delta</pre>
</div>

<h4>Batch Your Orders</h4>

<p>
  Every order produces order events that LEAN processes and that your handlers see.
  Rebalance less often and express the target portfolio through <a href="/docs/v2/writing-algorithms/algorithm-framework/portfolio-construction/key-concepts">portfolio targets</a> so LEAN issues the smallest set of orders that reaches the target.
  This shows up as a smaller <code>Transactions</code> series.
</p>

<h4>Don't Submit Orders That Get Rejected</h4>

<p>
  An invalid order is not free.
  LEAN still creates the order, runs it through the pre-trade checks, raises the order event, and keeps the record for the rest of the backtest, so every rejection costs both time and memory that you never get back.
  Algorithms that react to a rejection by resizing and resubmitting pay for it several times over.
</p>

<p>
  Check the conditions before you place the order rather than after LEAN refuses it.
  The <a href="/docs/v2/writing-algorithms/trading-and-orders/pre-trade-risk-control#02-Basic-Validation">Basic Validation</a> page lists what LEAN checks: the security is tradable, the market is open, the last known price is not zero, and the quantity is neither zero nor smaller than the lot size.
  Size the position from the buying power you have and skip the order when the result rounds to nothing.
</p>

<div class="section-example-container">
    <pre class="csharp">// Size the order from the available buying power and skip it when there is nothing to trade.
var quantity = CalculateOrderQuantity(symbol, weight);
if (quantity != 0) MarketOrder(symbol, quantity);</pre>
    <pre class="python"># Size the order from the available buying power and skip it when there is nothing to trade.
quantity = self.calculate_order_quantity(symbol, weight)
if quantity != 0:
    self.market_order(symbol, quantity)</pre>
</div>

<p>
  Orders placed while the algorithm is <a href="/docs/v2/writing-algorithms/historical-data/warm-up-periods">warming up</a> are rejected as well, so guard that code path with the <code class="csharp">IsWarmingUp</code><code class="python">is_warming_up</code> property.
</p>

<h4>Filter Tick Data at the Source</h4>

<p>
  Tick subscriptions deliver both trades and quotes.
  When the strategy only reads trades, add a <a href="/docs/v2/writing-algorithms/securities/filtering-data">security data filter</a> that drops the rest before the data reaches your algorithm.
  Filtering out quote ticks removes the bid and ask information that market order fill models use, so check that the fills still model your strategy correctly.
</p>

<h4>Train Machine Learning Models Outside the Time Step</h4>

<p>
  An algorithm must normally process each time step within 10 minutes.
  Fit models in the <a href="/docs/v2/research-environment/key-concepts/getting-started">Research Environment</a>, save them to the <a href="/docs/v2/writing-algorithms/object-store">Object Store</a>, and load them at runtime.
  You can go further and <a href="/docs/v2/writing-algorithms/machine-learning/key-concepts#10-Increase-Backtest-Speed">precompute the predictions themselves</a> so the algorithm streams them in as a custom universe dataset.
  When you have to fit inside the algorithm, use the <a href="/docs/v2/writing-algorithms/machine-learning/training-models"><code class="csharp">Train</code><code class="python">train</code> method</a>, which raises that limit for the training session.
</p>

<? include(DOCS_RESOURCES."/logging-statements/best-practices.php"); ?>
