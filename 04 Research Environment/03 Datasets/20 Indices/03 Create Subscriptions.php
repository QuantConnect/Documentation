<p>Follow these steps to subscribe to an Index security:</p>

<ol>
<?
$additionalImports = "using QuantConnect.Securities.Index;
";
include(DOCS_RESOURCES."/datasets/research-environment/load-csharp-assemblies.php");
?>
    <li>Create a <code>QuantBook</code>.</li>
    <div class="section-example-container">
        <pre class="csharp">var qb = new QuantBook();</pre>
        <pre class="python">qb = QuantBook()</pre>
    </div>
    <li>Call the <code>AddIndex</code> method with a ticker and then save a reference to the Index <code>Symbol</code>.</li>
    <div class="section-example-container">
        <pre class="csharp">var spx = qb.AddIndex("SPX").Symbol;
var vix = qb.AddIndex("VIX").Symbol;</pre>
        <pre class="python">spx = qb.add_index("SPX").symbol
vix = qb.add_index("VIX").symbol</pre>
    </div>
</ol>

<p>To view all of the available indices, see <a href="/docs/v2/writing-algorithms/datasets/tickdata/us-cash-indices#06-Supported-Indices">Supported Indices</a>.</p>