<p>Follow these steps to subscribe to a CFD security:</p>

<ol>
<?
$additionalImports = "using QuantConnect.Securities.Cfd;
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
    <li>Call the <code>AddCfd</code> method with a ticker and then save a reference to the CFD <code>Symbol</code>.</li>
    <div class="section-example-container">
        <pre class="csharp">var spx = qb.AddCfd("SPX500USD").Symbol;
var usb = qb.AddCfd("USB10YUSD").Symbol;</pre>
        <pre class="python">spx = qb.AddCfd("SPX500USD").Symbol
usb = qb.AddCfd("USB10YUSD").Symbol</pre>
    </div>
</ol>

<p>To view all of the available contracts, see <a href="/docs/v2/writing-algorithms/datasets/oanda/cfd-data#05-Supported-Assets">Supported Assets</a>.</p>
