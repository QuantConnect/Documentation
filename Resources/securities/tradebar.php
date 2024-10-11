<p><code>TradeBar</code> objects are price bars that consolidate individual trades from the exchanges. They contain the open, high, low, close, and volume of trading activity over a period of time.</p>
<img src='https://cdn.quantconnect.com/docs/i/dataformat-tradebar.png' class='img-responsive' alt="Tradebar decomposition"> 
<p>To get the <code>TradeBar</code> objects in the <code>Slice</code>, index the <code>Slice</code> or index the <code class="csharp">Bars</code><code class="python">bars</code> property of the <code>Slice</code> with the <?=$securityName?> <code class="csharp">Symbol</code><code class="python">symbol</code>. If the <?=$securityName?> doesn't actively trade or you are in the same time step as when you added the <?=$securityName?> subscription, the <code>Slice</code> may not contain data for your <code class="csharp">Symbol</code><code class="python">symbol</code>. To avoid issues, check if the <code>Slice</code> contains data for your <?=$securityName?> before you index the <code>Slice</code> with the <?=$securityName?> <code class="csharp">Symbol</code><code class="python">symbol</code>.</p>

<div class='section-example-container'>
    <pre class='csharp'>public override void OnData(Slice slice)
{
    // Check if the symbol is contained in TradeBars object
    if (slice.Bars.ContainsKey(<?=$cSharpVariable?>))
    {
        // Obtain the mapped TradeBar of the symbol
        var tradeBar = slice.Bars[<?=$cSharpVariable?>];
    }
}
</pre>
    <pre class='python'>def on_data(self, slice: Slice) -> None:
    # Obtain the mapped TradeBar of the symbol if any
    trade_bar = slice.bars.get(<?=$pythonVariable?>)   # None if not found</pre>
</div>


<p>You can also iterate through the <code>TradeBars</code> dictionary. The keys of the dictionary are the <code>Symbol</code> objects and the values are the <code>TradeBar</code> objects.</p>
<div class='section-example-container'>
    <pre class='csharp'>public override void OnData(Slice slice)
{
    // Iterate all received Symbol-TradeBar key-value pairs
    foreach (var kvp in slice.Bars)
    {
        var symbol = kvp.Key;
        var tradeBar = kvp.Value;
        var closePrice = tradeBar.Close;
    }
}</pre>
    <pre class='python'>def on_data(self, slice: Slice) -> None:
    # Iterate all received Symbol-TradeBar key-value pairs
    for symbol, trade_bar in slice.bars.items():
        close_price = trade_bar.close</pre>
</div>

<p><code>TradeBar</code> objects have the following properties:</p>    
<div data-tree='QuantConnect.Data.Market.TradeBar'></div>   
