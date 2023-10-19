<p><code>Tick</code> objects represent a price for the Index at a moment in time. <code>Tick</code> objects have the following properties:</p>
<div data-tree='QuantConnect.Data.Market.Tick'></div>

<p>Index ticks have a non-zero value for the <code>Price</code> property, but they have a zero value for the <code>BidPrice</code>, <code>BidSize</code>, <code>AskPrice</code>, and <code>AskSize</code> properties.</p>

<p> In backtests, LEAN groups ticks into one millisecond buckets. In live trading, LEAN groups ticks into ~70-millisecond buckets. To get the <code>Tick</code> objects in the <code>Slice</code>, index the <code>Ticks</code> property of the <code>Slice</code> with a <code>Symbol</code>. The <code>Slice</code> may not contain data for your <code>Symbol</code> at every time step. To avoid issues, check if the <code>Slice</code> contains data for your Index before you index the <code>Slice</code> with the Index <code>Symbol</code>.</p>

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

public void OnData(Ticks ticks)
{
    if (ticks.ContainsKey(_symbol))
    {
        foreach (var tick in ticks[_symbol])
        {
            var value = tick.Value;
        }
    }
}
</pre>
    <pre class='python'>def OnData(self, slice: Slice) -&gt; None:
    if self.symbol in slice.Ticks:
        ticks = slice.Ticks[self.symbol]
        for tick in ticks:
            value = tick.Value</pre>
</div>
