<? include(DOCS_RESOURCES."/securities/data-definitions/open-interest.html"); ?>

<p>To get open interest data, call the <span class=python><code>history</code> or <code>history[OpenInterest]</code></span><code class=csharp>History&lt;OpenInterest&gt;</code> method with the contract <code>Symbol</code> object(s).</p>

<div class="section-example-container">
    <pre class="csharp">var history = qb.History&lt;OpenInterest&gt;(contractSymbol, TimeSpan.FromDays(3));
foreach (var openInterest in history)
{
	Console.WriteLine(openInterest);
}</pre>
    <pre class="python"># DataFrame format
history_df = qb.history(OpenInterest, contract_symbol, timedelta(3))
display(history_df)

# OpenInterest objects
history = qb.history[OpenInterest](contract_symbol, timedelta(3))
for open_interest in history:
    print(open_interest)</pre>
</div>

<p><code>OpenInterest</code> objects have the following properties:</p>    
<div data-tree='QuantConnect.Data.Market.OpenInterest'></div>

<p>For more information about history requests, see <a href='/docs/v2/research-environment/datasets/key-concepts'>Key Concepts</a>.</p>