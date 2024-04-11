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
        <pre class="csharp">qb.set_time_zone(TimeZones.utc);</pre>
        <pre class="python">qb.set_time_zone(TimeZones.utc)</pre>
    </div>
    <li>Call the <code>AddCfd</code> method with a ticker and then save a reference to the CFD <code>Symbol</code>.</li>
    <div class="section-example-container">
        <pre class="csharp">var spx = qb.AddCfd("SPX500USD").Symbol;
var usb = qb.AddCfd("USB10YUSD").Symbol;</pre>
        <pre class="python">spx = qb.add_cfd("SPX500USD").symbol
usb = qb.add_cfd("USB10YUSD").symbol</pre>
    </div>
</ol>

<p>To view all of the available contracts, see <a href="/docs/v2/writing-algorithms/datasets/oanda/cfd-data#06-Supported-Assets">Supported Assets</a>.</p>
