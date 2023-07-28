<p>Follow these steps to subscribe to a US Equity security:</p>

<ol>
<?
$additionalImports = "";
include(DOCS_RESOURCES."/datasets/research-environment/load-csharp-assemblies.php");
?>
    <li>Create a <code>QuantBook</code>.</li>
    <div class="section-example-container">
        <pre class="csharp">var qb = new QuantBook();</pre>
        <pre class="python">qb = QuantBook()</pre>
    </div>
    <li>Call the <code>AddEquity</code> method with a ticker and then save a reference to the US Equity <code>Symbol</code>.</li>
    <div class="section-example-container">
        <pre class="csharp">var spy = qb.AddEquity("SPY").Symbol;
var tlt = qb.AddEquity("TLT").Symbol;</pre>
        <pre class="python">spy = qb.AddEquity("SPY").Symbol
tlt = qb.AddEquity("TLT").Symbol</pre>
    </div>
</ol>

<p>To view the supported assets in the US Equities dataset, see the <a href="/data/tree/equity/usa/daily">Data Explorer</a>.</p>
