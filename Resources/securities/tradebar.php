<p><code>TradeBar</code> objects are price bars that consolidate individual trades from the exchanges. They contain the open, high, low, close, and volume of trading activity over a period of time.</p>
<img src='https://cdn.quantconnect.com/docs/i/dataformat-tradebar.png' class='img-responsive' alt="Tradebar decomposition">
<p><code>TradeBar</code> objects have the following properties:</p>    
<div data-tree='QuantConnect.Data.Market.TradeBar'></div>    
<p>To get the <code>TradeBar</code> objects in the <code>Slice</code>, index the <code>Slice</code> or index the <code>Bars</code> property of the <code>Slice</code> with the <?=$securityName?> <code>Symbol</code>. If the <?=$securityName?> doesn't actively trade or you are in the same time step as when you added the <?=$securityName?> subscription, the <code>Slice</code> may not contain data for your <code>Symbol</code>. To avoid issues, check if the <code>Slice</code> contains data for your <?=$securityName?> before you index the <code>Slice</code> with the <?=$securityName?> <code>Symbol</code>.</p>

<div class='section-example-container'>
    <pre class='csharp'>public override void OnData(Slice slice)
{
    if (slice.Bars.ContainsKey(<?=$cSharpVariable?>))
    {
        var tradeBar = slice.Bars[<?=$cSharpVariable?>];
    }
}
</pre>
    <pre class='python'>def OnData(self, slice: Slice) -> None:
    if <?=$pythonVariable?> in slice.Bars:
        trade_bar = slice.Bars[<?=$pythonVariable?>]</pre>
</div>


<p>You can also iterate through the <code>TradeBars</code> dictionary. The keys of the dictionary are the <code>Symbol</code> objects and the values are the <code>TradeBar</code> objects.</p>
<div class='section-example-container'>
    <pre class='csharp'>public override void OnData(Slice slice)
{
    foreach (var kvp in slice.Bars)
    {
        var symbol = kvp.Key;
        var tradeBar = kvp.Value;
        var closePrice = tradeBar.Close;
    }
}</pre>
    <pre class='python'>def OnData(self, slice: Slice) -> None:
    for symbol, trade_bar in slice.Bars.items():
        close_price = trade_bar.Close</pre>
</div>