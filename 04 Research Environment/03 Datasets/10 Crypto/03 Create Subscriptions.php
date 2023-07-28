<p>Follow these steps to subscribe to a Crypto security:</p>

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
    <li>Call the <code>AddCrypto</code> method with a ticker and then save a reference to the Crypto <code>Symbol</code>.</li>
    <div class="section-example-container">
        <pre class="csharp">var btcusd = qb.AddCrypto("BTCUSD").Symbol;
var ethusd = qb.AddCrypto("ETHUSD").Symbol;</pre>
        <pre class="python">btcusd = qb.AddCrypto("BTCUSD").Symbol
ethusd = qb.AddCrypto("ETHUSD").Symbol</pre>
    </div>
</ol>

<p>To view the supported assets in the Crypto datasets, see the <span class='page-section-name'>Supported Assets</span> section of the <a href='/docs/v2/writing-algorithms/datasets/coinapi'>CoinAPI dataset listings</a>.</p>
