<p>Follow these steps to subscribe to an Equity Option universe:</p>

<ol>
<?
$additionalImports = "using QuantConnect.Securities.Option;
using QuantConnect.Data.UniverseSelection;
";
include(DOCS_RESOURCES."/datasets/research-environment/load-csharp-assemblies.php");
?>
    <li>Create a <code>QuantBook</code>.</li>
    <div class="section-example-container">
        <pre class="csharp">var qb = new QuantBook();</pre>
        <pre class="python">qb = QuantBook()</pre>
    </div>

    <li>Subscribe to the underlying Equity with raw data normalization and save a reference to the Equity <code>Symbol</code>.</li>
    <div class="section-example-container">
        <pre class="csharp">var equitySymbol = qb.AddEquity("SPY", dataNormalizationMode: DataNormalizationMode.Raw).Symbol;</pre>
        <pre class="python">equity_symbol = qb.add_equity("SPY", data_normalization_mode=DataNormalizationMode.RAW).symbol</pre></div><div class="csharp section-example-container">
    </div>
    <p>To view the supported underlying assets in the US Equity Options dataset, see the <a href="/datasets/algoseek-us-equity-options/explorer">Data Explorer</a>.</p>

    <li>Call the <code class="csharp">AddOption</code><code class="python">add_option</code> method with the underlying Equity <code>Symbol</code>.</li>
    <div class="section-example-container">
        <pre class="csharp">var option = qb.AddOption(equitySymbol);</pre>
        <pre class="python">option = qb.add_option(equity_symbol)</pre>
    </div>
</ol>
