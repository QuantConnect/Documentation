<? include(DOCS_RESOURCES."/securities/data-definitions/quotebar.html"); ?>
    
<p>To get the <code>QuoteBar</code> objects in the <code>Slice</code>, index the <code>QuoteBars</code> property of the <code>Slice</code> with the <?=$securityName?> <code class="csharp">Symbol</code><code class="python">symbol</code>. If the <?=$securityName?> doesn't actively get quotes or you are in the same time step as when you added the <?=$securityName?> subscription, the <code>Slice</code> may not contain data for your <code class="csharp">Symbol</code><code class="python">symbol</code>. To avoid issues, check if the <code>Slice</code> contains data for your <?=$securityName?> before you index the <code>Slice</code> with the <?=$securityName?> <code class="csharp">Symbol</code><code class="python">symbol</code>.</p>
    
<div class='section-example-container'>
    <pre class='csharp'>public override void OnData(Slice slice)
{
    // Check if the symbol is contained in QuoteBars object
    if (slice.QuoteBars.ContainsKey(<?=$cSharpVariable?>))
    {
        // Obtain the mapped QuoteBar of the symbol
        var quoteBar = slice.QuoteBars[<?=$cSharpVariable?>];
    }
}
</pre>
    <pre class='python'>def on_data(self, slice: Slice) -> None:
    # Obtain the mapped QuoteBar of the symbol if any
    quote_bar = slice.quote_bars.get(<?=$pythonVariable?>)   # None if not found</pre>
</div>

<p>You can also iterate through the <code>QuoteBars</code> dictionary. The keys of the dictionary are the <code>Symbol</code> objects and the values are the <code>QuoteBar</code> objects.</p>
<div class='section-example-container'>
    <pre class='csharp'>public override void OnData(Slice slice)
{
    // Iterate all received Symbol-QuoteBar key-value pairs
    foreach (var kvp in slice.QuoteBars)
    {
        var symbol = kvp.Key;
        var quoteBar = kvp.Value;
        var askPrice = quoteBar.Ask.Close;
    }
}</pre>
    <pre class='python'>def on_data(self, slice: Slice) -> None:
    # Iterate all received Symbol-QuoteBar key-value pairs
    for symbol, quote_bar in slice.quote_bars.items():
        ask_price = quote_bar.ask.close</pre>
</div>

<p><code>QuoteBar</code> objects let LEAN incorporate spread costs into your <a href='/docs/v2/writing-algorithms/reality-modeling/trade-fills/key-concepts'>simulated trade fills</a> to make backtest results more realistic.</p>

<p><code>QuoteBar</code> objects have the following properties:</p>
<div data-tree='QuantConnect.Data.Market.QuoteBar'></div>
