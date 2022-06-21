<p><code>Tick</code> objects represent a price for the Index at a moment in time. <code>Tick</code> objects have the following properties:</p>
<div data-tree='QuantConnect.Data.Market.Tick'></div>

<p>Index ticks have a non-zero value for the <code>Price</code> property, but they have a zero value for the <code>BidPrice</code>, <code>BidSize</code>, <code>AskPrice</code>, and <code>AskSize</code> properties.</p>

<p> In backtests, LEAN groups ticks into one millisecond buckets. In live trading, LEAN groups ticks into ~70-millisecond buckets. To get the <code>Tick</code> objects in the <code>Slice</code>, index the <code>Ticks</code> property of the <code>Slice</code> with a <code>Symbol</code>. The <code>Slice</code> may not contain data for your <code>Symbol</code> at every time step. To avoid issues, check if the <code>Slice</code> contains data for your Index before you index the <code>Slice</code> with the Index <code>Symbol</code>.</p>

<div class='section-example-container'>
    <pre class='csharp'>public override void OnDate(TradeBar data)
{
    if (data.Ticks.ContainsKey(symbol))
    {
        var ticks = data.Ticks[symbol];

        foreach (var tick in ticks)
        {
            if (tick.TickType == TickType.Trade)
            {
                var price = tick.Price;
                var qty = tick.Quantity;
            }
            else if (tick.TickType == TickType.Quote)
            {
                var bidPrice = tick.BidPrice;
                var bidSize = tick.BidSize;
                var askPrice = tick.AskPrice;
                var askSize = tick.AskSize;
            }
        }
    }
}</pre>
    <pre class='python'>def OnData(self, data: Slice) -&gt; None:
    if data.Ticks.ContainsKey(symbol):
        ticks = data.Ticks[symbol]

        for tick in ticks:
            if tick.TickType == TickType.Trade:
                price = tick.Price
                qty = tick.Quantity
            
            elif tick.TickType == TickType.Quote:
                bid_price = tick.BidPrice
                bid_size = tick.BidSize
                ask_price = tick.AskPrice
                ask_size = tick.AskSize</pre>
</div>