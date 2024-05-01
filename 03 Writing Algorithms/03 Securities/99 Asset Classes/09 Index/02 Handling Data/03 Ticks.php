<p><code>Tick</code> objects represent a price for the Index at a moment in time. <code>Tick</code> objects have the following properties:</p>
<div data-tree='QuantConnect.Data.Market.Tick'></div>

<p>Index ticks have a non-zero value for the <code class="csharp">Price</code><code class="python">price</code> property, but they have a zero value for the <code class="csharp">BidPrice</code><code class="python">bid_price</code>, <code class="csharp">BidSize</code><code class="python">bid_size</code>, <code class="csharp">AskPrice</code><code class="python">ask_price</code>, and <code class="csharp">AskSize</code><code class="python">ask_size</code> properties.</p>

<p>In backtests, LEAN groups ticks into one millisecond buckets. In live trading, LEAN groups ticks into ~70-millisecond buckets. To get the <code>Tick</code> objects in the <code>Slice</code>, index the <code>Ticks</code> property of the <code>Slice</code> with a <code>Symbol</code>. The <code>Slice</code> may not contain data for your <code>Symbol</code> at every time step. To avoid issues, check if the <code>Slice</code> contains data for your Index before you index the <code>Slice</code> with the Index <code>Symbol</code>.</p>

<div class='section-example-container'>
    <pre class='csharp'>public override void OnData(Slice slice)
{
    if (slice.Ticks.ContainsKey(_symbol))
    {
        var ticks = slice.Ticks[_symbol];
        foreach (var tick in ticks)
        {
            var value = tick.Value;
        }
    }
}
</pre>
    <pre class='python'>def on_data(self, slice: Slice) -&gt; None:
    if self._symbol in slice.ticks:
        ticks = slice.ticks[self._symbol]
        for tick in ticks:
            value = tick.value</pre>
</div>
