<p><code>Tick</code> objects represent a single trade or quote at a moment in time. A trade tick is a record of a transaction or sale for the security. A quote tick is an offer to buy or sell a security for a specific price. In backtests, LEAN groups ticks into one millisecond buckets. In live trading, LEAN groups ticks into ~70 millisecond buckets.</p>

<p><code>Tick</code> objects have the following properties:</p>
<div data-tree="QuantConnect.Data.Market.Tick"></div>

<p>Trade ticks have a non-zero value for the <code>Quantity</code> and <code>Price</code> properties but they a zero value for the <code>BidPrice</code>, <code>BidSize</code>, <code>AskPrice</code>, and <code>AskSize</code> properties. Quote ticks have non-zero values for <code>BidPrice</code> and <code>BidSize</code> properties or have non-zero values for <code>AskPrice</code> and <code>AskSize</code> properties.</p>

<p>To get the <code>Tick</code> objects in the <code>Slice</code>, index the <code>Ticks</code> property of the <code>Slice</code> with a <code>Symbol</code>. If the security doesn't actively trade or you are in the same time step as when you added the security subscription, the <code>Slice</code> may not contain data for your <code>Symbol</code>. To avoid issues, check if the <code>Slice</code> contains data for your security before you index the <code>Slice</code> with the security <code>Symbol</code>.</p>

<div class="section-example-container">
    <pre class="csharp">// Example of accessing Tick objects in OnData
// The examples on this page should check if the slice contains the data before indexing it.</pre>
    <pre class="python"># Example of accessing Tick objects in OnData
# The examples on this page should check if the slice contains the data before indexing it.</pre>
</div>

<p>Tick data is raw and unfiltered, so it can contain bad ticks that skew your trade results. For example, some ticks come from dark pools, which aren't tradable. We recommend you only use tick data if you understand the risks and are able to perform your own online tick filtering. If <a href='/docs/v2/our-platform/live-trading/data-feeds/us-equities#05-Suspicious-Ticks'>we detect a ticks may be suspicious</a>, we mark it with their <code>Suspicious</code> flag.</p>
