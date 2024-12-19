<p>To get historical values for the borrow fee rate, borrow rebate rate, or shortable quantity of a US Equity, call the <code class="csharp">FeeRate</code><code class="python">fee_rate</code>, <code class="csharp">RebateRate</code><code class="python">rebate_rate</code>, or <code class="csharp">ShortableQuantity</code><code class="python">shortable_quantity</code> methods of the <a href='/docs/v2/writing-algorithms/reality-modeling/short-availability/key-concepts'>shortable provider</a>.</p>

<div class="section-example-container">
    <pre class="csharp">// Pass a time argument to shortable provider to get historical values.
var feeRate = security.ShortableProvider.FeeRate(symbol, time);
var rebateRate = security.ShortableProvider.RebateRate(symbol, time);
var shortableQuantity = security.ShortableProvider.ShortableQuantity(symbol, time);</pre>
    <pre class="python"># Pass a time argument to shortable provider to get historical values.
fee_rate = security.shortable_provider.fee_rate(symbol, time)
rebate_rate = security.shortable_provider.rebate_rate(symbol, time)
shortable_quantity = security.shortable_provider.shortable_quantity(symbol, time)</pre>
</div>
