<h4>Log Sparingly</h4>

<p>
  Every log statement costs execution time and counts against your <a href="/docs/v2/cloud-platform/organizations/resources#09-Log-Quotas">log quota</a>, so a statement in a handler that runs on every time step can slow an algorithm down and exhaust the quota in a single run.
  Never log without a bound inside the <code class="csharp">OnData</code><code class="python">on_data</code> event handler or inside a Scheduled Event that fires often.
  Log the condition you are investigating, not every pass through the handler.
</p>

<p>
  Use the <code class="csharp">Log</code><code class="python">log</code> method rather than the <code class="csharp">Debug</code><code class="python">debug</code> method for routine records.
  Debug messages go through the messaging system, which rate limits them to protect your browser and slows the algorithm.
  Reserve debug statements for the few messages you want to stand out in the terminal.
</p>

<h4>Gate Diagnostics Behind a Verbosity Level</h4>

<p>
  Rather than adding and removing diagnostic statements each time you investigate something, route them through one method that checks a verbosity level.
  You keep the statements in place and turn them off for the runs where you do not need them.
</p>

<div class="section-example-container">
    <pre class="csharp">// Log the message only when the algorithm runs at or above the given verbosity level.
private void Trace(int level, string message)
{
    if (level &lt;= _logLevel) Log(message);
}</pre>
    <pre class="python"># Log the message only when the algorithm runs at or above the given verbosity level.
def _trace(self, level, message):
    if level &lt;= self._log_level:
        self.log(message)</pre>
</div>

<p>
  The caller builds the message before the method runs, so string formatting still costs you even when the method discards the result.
  In the handlers that run on every time step, check the level at the call site so you skip the formatting too.
</p>

<div class="section-example-container">
    <pre class="csharp">// Skip building the message when the level is off.
if (_logLevel &gt;= 2) Trace(2, $"{Time}: {symbol} at {price}");</pre>
    <pre class="python"># Skip building the message when the level is off.
if self._log_level &gt;= 2:
    self._trace(2, f"{self.time}: {symbol} at {price}")</pre>
</div>

<h4>Record Trade Information on the Order</h4>

<p>
  To record why a trade happened, pass a <code class="csharp">tag</code><code class="python">tag</code> argument to the order method instead of writing a log statement.
  The tag travels with the <a href="/docs/v2/writing-algorithms/trading-and-orders/order-management/order-tickets">order</a> and appears in the results, so you keep the context of each trade without the cost of logging it.
</p>

<div class="section-example-container">
    <pre class="csharp">// Record the reason for the trade on the order instead of in the log.
StopMarketOrder(symbol, -quantity, stopPrice, tag: "stop loss");</pre>
    <pre class="python"># Record the reason for the trade on the order instead of in the log.
self.stop_market_order(symbol, -quantity, stop_price, tag="stop loss")</pre>
</div>
