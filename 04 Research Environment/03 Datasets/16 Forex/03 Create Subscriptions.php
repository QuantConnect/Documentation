<p>Follow these steps to subscribe to a Forex security:</p>

<ol>
<?
$additionalImports = "using QuantConnect.Securities.Forex;
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
    <li>Call the <code>AddForex</code> method with a ticker and then save a reference to the Forex <code>Symbol</code>.</li>
    <div class="section-example-container">
        <pre class="csharp">var eurusd = qb.AddForex("EURUSD").Symbol;
var gbpusd = qb.AddForex("GBPUSD").Symbol;</pre>
        <pre class="python">eurusd = qb.AddForex("EURUSD").Symbol
gbpusd = qb.AddForex("GBPUSD").Symbol</pre>
    </div>
</ol>

<p>To view all of the available Forex pairs, see <a href='/docs/v2/writing-algorithms/datasets/oanda/forex-data#06-Supported-Assets'>Supported Assets</a>.</p>
