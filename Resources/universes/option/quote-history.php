<? include(DOCS_RESOURCES."/securities/data-definitions/quotebar.html"); ?>

<p>To get quote data, call the <span class=python><code>history</code> or <code>history[QuoteBar]</code></span><code class=csharp>History&lt;QuoteBar&gt;</code> method with the contract <code>Symbol</code> object(s).</p>

<div class="section-example-container">
    <pre class="csharp">var history = qb.History&lt;QuoteBar&gt;(<?=$variableNameC?>, TimeSpan.FromDays(3));
foreach (var quoteBar in history)
{
    Console.WriteLine(quoteBar);
}</pre>
    <pre class="python"># DataFrame format
history_df = qb.history(QuoteBar, <?=$variableNamePy?>, timedelta(3))
display(history_df)

# QuoteBar objects
history = qb.history[QuoteBar](<?=$variableNamePy?>, timedelta(3))
for quote_bar in history:
    print(quote_bar)</pre>
</div>

<p><code>QuoteBar</code> objects have the following properties:</p>    
<div data-tree='QuantConnect.Data.Market.QuoteBar'></div>
