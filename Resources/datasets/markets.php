<p>The datasets integrated into the Dataset Market cover many markets. The <code>Market</code> enumeration has the following members:</p>
<div data-tree='QuantConnect.Market'></div>

<p>LEAN can usually determine the correct market based on the ticker you provide when you create the security subscription. To manually set the market for a security, pass a <code>market</code> argument when you create the security subscription.</p>

<div class='section-example-container'>
    <pre class='csharp'><?=$writingAlgorithms ? "" : "qb." ?>AddEquity("SPY", market: Market.USA);</pre>
    <pre class='python'><?=$writingAlgorithms ? "self" : "qb" ?>.add_equity("SPY", market=Market.USA)</pre>
</div>