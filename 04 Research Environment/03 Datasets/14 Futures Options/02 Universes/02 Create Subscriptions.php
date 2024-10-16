<p>Follow these steps to subscribe to a Futures Options universe:</p>

<ol>
<?
$additionalImports = "using QuantConnect.Securities.Future;
using QuantConnect.Securities.FutureOption;
using QuantConnect.Data.UniverseSelection;
";
include(DOCS_RESOURCES."/datasets/research-environment/load-csharp-assemblies.php");
?>
    <li>Create a <code>QuantBook</code>.</li>
    <div class="section-example-container">
        <pre class="csharp">var qb = new QuantBook();</pre>
        <pre class="python">qb = QuantBook()</pre>
    </div>

    <li><a href="/docs/v2/research-environment/datasets/futures#03-Create-Subscriptions">Add the underlying Future</a>.</li>
    <div class="section-example-container">
        <pre class="csharp">var future = qb.AddFuture(Futures.Indices.SP500EMini);</pre>
        <pre class="python">future = qb.add_future(Futures.Indices.SP_500_E_MINI)</pre>
    </div>
    <p>To view the available underlying Futures in the US Future Options dataset, see <a href="/docs/v2/writing-algorithms/datasets/algoseek/us-future-options#07-Supported-Assets">Supported Assets</a>.</p>
</ol>
