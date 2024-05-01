<p>Follow these steps to subscribe to an Equity security:</p>

<ol>
<?
$additionalImports = "using QuantConnect.Data.Fundamental;
";
include(DOCS_RESOURCES."/datasets/research-environment/load-csharp-assemblies.php");
?>
    <li>Create a <code>QuantBook</code>.</li>
    <div class="section-example-container">
        <pre class="csharp">var qb = new QuantBook();</pre>
        <pre class="python">qb = QuantBook()</pre>
    </div>
    <li>Call the <code class="csharp">AddEquity</code><code class="python">add_equity</code> method with a ticker and then save a reference to the Equity <code>Symbol</code>.</li>

<div class="section-example-container">
    <pre class="csharp">var symbols = new [] 
{
    "AAL",  // American Airlines Group, Inc.
    "ALGT", // Allegiant Travel Company
    "ALK",  // Alaska Air Group, Inc.
    "DAL",  // Delta Air Lines, Inc.
    "LUV",  // Southwest Airlines Company
    "SKYW", // SkyWest, Inc.
    "UAL"   // United Air Lines
}
.Select(ticker =&gt; qb.AddEquity(ticker, Resolution.Daily).Symbol);</pre>
    <pre class="python">symbols = [
    qb.add_equity(ticker, Resolution.DAILY).symbol
    for ticker in [
        "AAL",   # American Airlines Group, Inc.
        "ALGT",  # Allegiant Travel Company
        "ALK",   # Alaska Air Group, Inc.
        "DAL",   # Delta Air Lines, Inc.
        "LUV",   # Southwest Airlines Company
        "SKYW",  # SkyWest, Inc.
        "UAL"    # United Air Lines
    ]
]</pre>
</div>

</ol>
