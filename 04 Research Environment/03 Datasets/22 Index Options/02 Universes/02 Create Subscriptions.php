<p>Follow these steps to subscribe to an Index Option universe:</p>

<ol>
<?
$additionalImports = "using QuantConnect.Securities.Index;
using QuantConnect.Securities.IndexOption;
using QuantConnect.Data.UniverseSelection;
";
include(DOCS_RESOURCES."/datasets/research-environment/load-csharp-assemblies.php");
?>
    <li>Create a <code>QuantBook</code>.</li>
    <div class="section-example-container">
        <pre class="csharp">var qb = new QuantBook();</pre>
        <pre class="python">qb = QuantBook()</pre>
    </div>

    <li><a href='/docs/v2/research-environment/datasets/indices#03-Create-Subscriptions'>Add the underlying Index</a>.</li>
    <div class="section-example-container">
        <pre class="csharp">var indexSymbol = qb.AddIndex("SPX", Resolution.Minute).Symbol;</pre>
        <pre class="python">index_symbol = qb.add_index("SPX", Resolution.MINUTE).symbol</pre>
    </div>
    <p>To view the available Indices, see <a href="/docs/v2/writing-algorithms/datasets/algoseek/us-index-options#08-Supported-Assets">Supported Assets</a>.</p>
    <p>If you do not pass a resolution argument, <code class="csharp">Resolution.Minute</code><code class="python">Resolution.MINUTE</code> is used by default. <br></p>

    <li>Call the <code class="csharp">AddIndexOption</code><code class="python">add_index_option</code> method with the underlying <code>Index</code> <code>Symbol</code> and, if you want non-standard Index Options, the <a href='/docs/v2/writing-algorithms/datasets/algoseek/us-index-options#08-Supported-Assets'>target Option ticker</a>.</li>
    <div class="section-example-container">
        <pre class="csharp">var option = qb.AddIndexOption(indexSymbol);</pre>
        <pre class="python">option = qb.add_index_option(index_symbol)</pre>
    </div>
</ol>
