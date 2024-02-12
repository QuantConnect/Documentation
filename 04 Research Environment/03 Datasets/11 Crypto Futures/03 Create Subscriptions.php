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
    <li><span class='qualifier'>(Optional)</span> <a href='/docs/v2/research-environment/initialization#04-Set-Time-Zone'>Set the time zone</a> to the <a href='/docs/v2/writing-algorithms/key-concepts/time-modeling/time-zones#05-Data-Time-Zone'>data time zone</a>.</li>
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

<p>To view the supported assets in the Crypto Futures datasets, see the <a href="/datasets/binance-cryptofuture-price-data/explorer">Data Explorer</a>.</p>
