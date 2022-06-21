<p>You can't trade Indices, but <code>TradeBar</code> objects are bars that represent the open, high, low, close, and volume of an Index price over a period of time.</p>
<img src='https://cdn.quantconnect.com/i/tu/index-trade-bar.png' class='img-responsive'>
<p><code>TradeBar</code> objects have the following properties:</p>    
<div data-tree='QuantConnect.Data.Market.TradeBar'></div>  
  
<p>To get the <code>TradeBar</code> objects in the <code>Slice</code>, index the <code>Slice</code> or index the <code>Bars</code> property of the <code>Slice</code> with the Index <code>Symbol</code>. The <code>Slice</code> may not contain data for your <code>Symbol</code> at every time step. To avoid issues, check if the <code>Slice</code> contains data for your Index before you index the <code>Slice</code> with the Index <code>Symbol</code>.</p>
    
<div class='section-example-container'>
    <pre class='csharp'>public override void OnDate(Slice data)
{
    if (data.Bars.ContainsKey(symbol))
    {
        var open = data.Bars[symbol].Open;
        var high = data.Bars[symbol].High;
        var low = data.Bars[symbol].Low;
        var close = data.Bars[symbol].Close;
        var volume = data.Bars[symbol].Volume;
    }
}

public override void OnDate(TradeBar data)
{
    if (data.ContainsKey(symbol))
    {
        var open = data[symbol].Open;
        var high = data[symbol].High;
        var low = data[symbol].Low;
        var close = data[symbol].Close;
        var volume = data[symbol].Volume;
    }
}</pre>
    <pre class='python'>def OnData(self, data: Slice) -&gt; None:
    if data.Bars.ContainsKey(symbol):
        open = data.Bars[symbol].Open
        high = data.Bars[symbol].High
        low = data.Bars[symbol].Low
        close = data.Bars[symbol].Close
        volume = data.Bars[symbol].Volume</pre>
</div>
