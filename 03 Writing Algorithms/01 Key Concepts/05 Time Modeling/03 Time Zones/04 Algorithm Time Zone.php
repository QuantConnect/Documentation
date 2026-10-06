<?
include(DOCS_RESOURCES."/initialization/set-time-zone.php");
?>

<p>To get the time zone of your algorithm, use the <code class="csharp">TimeZone</code><code class="python">time_zone</code> property.</p>

<div class="section-example-container">
<pre class="python"># The algorithm timezone property can assist with multi-asset trading.
time_zone = self.time_zone</pre>
<pre class="csharp">// The algorithm timezone property can assist with multi-asset trading.
var timeZone = TimeZone;</pre>
</div> 

 <p>To get the algorithm time in Coordinated Universal Time (UTC), use the <code class="csharp">UtcTime</code><code class="python">utc_time</code> property.</p>

<div class="section-example-container">
<pre class="python"># Access the current UTC time to coordinate multi-timezone events or improve logging.
utc_time = self.utc_time</pre>
<pre class="csharp">// Access the current UTC time to coordinate multi-timezone events or improve logging.
var utcTime = UtcTime;</pre>
</div> 

<p class="csharp">The <code>Time</code> and <code>UtcTime</code> objects have no time zone. LEAN maintains their state to be consistent.</p>

<p class="python">The <code>time</code> property is a naive <code>datetime</code> object in the algorithm time zone. The <code>time</code> and <code>end_time</code> properties of data objects are also naive and use the exchange time zone. The <code>utc_time</code> property of the algorithm and of <a href="/docs/v2/writing-algorithms/trading-and-orders/order-events">order events</a> is a timezone-aware <code>datetime</code> object in UTC.</p>

<p class="python">If you subtract a naive <code>datetime</code> from a timezone-aware one, Python raises a <code>TypeError</code>. To compare two times, use values of the same kind. Save <code>self.utc_time</code> when you later subtract it from the <code>utc_time</code> of an order event. Save <code>self.time</code> when you later compare it with <code>self.time</code> or the times of data objects.</p>

<div class="section-example-container python">
<pre class="python">def on_data(self, slice: Slice) -&gt; None:
    if not self.portfolio.invested:
        # Save the UTC time before you place the order because backtests can fill it immediately.
        self._order_utc_time = self.utc_time
        self.market_order(self._symbol, 1)

def on_order_event(self, order_event: OrderEvent) -&gt; None:
    if order_event.status == OrderStatus.FILLED:
        # Both values are timezone-aware, so the subtraction succeeds.
        time_to_fill = order_event.utc_time - self._order_utc_time</pre>
</div>
