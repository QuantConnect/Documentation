<? include(DOCS_RESOURCES."/securities/data-definitions/tradebar.html"); ?>

<p>To get trade data, call the <span class=python><code>history</code> or <code>history[TradeBar]</code></span><code class=csharp>History&lt;TradeBar&gt;</code> method with the contract <code>Symbol</code> object(s).</p>

<div class="section-example-container">
    <pre class="csharp">var history = qb.History&lt;TradeBar&gt;(contractSymbol, TimeSpan.FromDays(3));
foreach (var tradeBar in history)
{
	Console.WriteLine(tradeBar);
}</pre>
    <pre class="python"># DataFrame format
history_df = qb.history(TradeBar, contract_symbol, timedelta(3))
display(history_df)

# TradeBar objects
history = qb.history[TradeBar](contract_symbol, timedelta(3))
for trade_bar in history:
    print(trade_bar)</pre>
</div>

<p><code>TradeBar</code> objects have the following properties:</p>    
<div data-tree='QuantConnect.Data.Market.TradeBar'></div>

<p>For more information about history requests, see <a href='/docs/v2/research-environment/datasets/key-concepts'>Key Concepts</a>.</p>