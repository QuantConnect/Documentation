<p>You can't trade Indices, but <code>TradeBar</code> objects are bars that represent the open, high, low, and close of an Index price over a period of time.</p>
<img src='https://cdn.quantconnect.com/i/tu/index-trade-bar.png' class='img-responsive' alt="Trade bar breakdown">
<p><code>TradeBar</code> objects have the following properties:</p>    
<div data-tree='QuantConnect.Data.Market.TradeBar'></div>  
  
<p>To get the <code>TradeBar</code> objects in the <code>Slice</code>, index the <code>Slice</code> or index the <code>Bars</code> property of the <code>Slice</code> with the Index <code>Symbol</code>. The <code>Slice</code> may not contain data for your <code>Symbol</code> at every time step. To avoid issues, check if the <code>Slice</code> contains data for your Index before you index the <code>Slice</code> with the Index <code>Symbol</code>.</p>
    
<div class='section-example-container'>
    <pre class='csharp'>public override void OnData(Slice slice)
{
    if (slice.Bars.ContainsKey(_symbol))
    {
        var tradeBar = slice.Bars[_symbol];
        var value = tradeBar.Value;
    }
}

public void OnData(TradeBars tradeBars)
{
    if (tradeBars.ContainsKey(_symbol))
    {
        var tradeBar = tradeBars[_symbol];
        var value = tradeBar.Value;
    }
}
</pre>
    <pre class='python'>def OnData(self, slice: Slice) -&gt; None:
    if self.symbol in slice.Bars:
        trade_bar = slice.Bars[self.symbol]
        value = trade_bar.Value</pre>
</div>
