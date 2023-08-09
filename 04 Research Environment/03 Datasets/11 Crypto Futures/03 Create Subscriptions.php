<p>Follow these steps to subscribe to a perpetual Crypto Futures contract:</p>

<ol>
<?
$additionalImports = "using QuantConnect.Securities.CryptoFuture;
";
include(DOCS_RESOURCES."/datasets/research-environment/load-csharp-assemblies.php");
?>
    <li>Create a <code>QuantBook</code>.</li>
    <div class="section-example-container">
        <pre class="csharp">var qb = new QuantBook();</pre>
        <pre class="python">qb = QuantBook()</pre>
    </div>
    <div class="section-example-container">
        <pre class="csharp">qb.SetTimeZone(TimeZones.Utc);</pre>
        <pre class="python">qb.SetTimeZone(TimeZones.Utc)</pre>
    </div>
    <li>Call the <code>AddCryptoFuture</code> method with a ticker and then save a reference to the Crypto Future <code>Symbol</code>.</li>
    <div class="section-example-container">
        <pre class="csharp">var btcusd = qb.AddCryptoFuture("BTCUSD").Symbol;
var ethusd = qb.AddCryptoFuture("ETHUSD").Symbol;</pre>
        <pre class="python">btcusd = qb.AddCryptoFuture("BTCUSD").Symbol
ethusd = qb.AddCryptoFuture("ETHUSD").Symbol</pre>
    </div>
</ol>

<p>To view the supported assets in the Crypto Futures datasets, see the <a href="/data/tree/cryptofuture/binance/daily">Data Explorer</a>.</p>
