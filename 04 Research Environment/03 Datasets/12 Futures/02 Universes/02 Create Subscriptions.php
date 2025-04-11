<p>Follow these steps to subscribe to a Futures universe:</p>

<ol>
<?
$additionalImports = "using QuantConnect.Securities.Future;
using QuantConnect.Securities;
using QuantConnect.Data.Market;
";
include(DOCS_RESOURCES."/datasets/research-environment/load-csharp-assemblies.php");
?>
    <li>Create a <code>QuantBook</code>.</li>
    <div class="section-example-container">
        <pre class="csharp">var qb = new QuantBook();</pre>
        <pre class="python">qb = QuantBook()</pre>
    </div>
    <li>Call the <code class="csharp">AddFuture</code><code class="python">add_future</code> method with a ticker, resolution, and <a href="/docs/v2/writing-algorithms/universes/futures#12-Continous-Contracts">contract rollover settings</a>.</li>
    <div class="section-example-container">
        <pre class="csharp">var future = qb.AddFuture(
    Futures.Indices.SP500EMini, 
    Resolution.Minute,
    dataNormalizationMode: DataNormalizationMode.BackwardsRatio,
    dataMappingMode: DataMappingMode.LastTradingDay,
    contractDepthOffset: 0
);</pre>
        <pre class="python">future = qb.add_future(
    Futures.Indices.SP_500_E_MINI, 
    Resolution.MINUTE,
    data_normalization_mode=DataNormalizationMode.BACKWARDS_RATIO,
    data_mapping_mode=DataMappingMode.LAST_TRADING_DAY,
    contract_depth_offset=0
)</pre>
    </div>
    <p>To view the available tickers in the US Futures dataset, see <a href='/docs/v2/writing-algorithms/datasets/algoseek/us-futures#08-Supported-Assets'>Supported Assets</a>.</p>
    <p>If you omit any of the arguments after the ticker, see the following table for their default values:</p>
    <table class="qc-table table">
        <thead>
            <tr>
                <th>Argument</th>
                <th>Default Value</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><code>resolution</code></td>
                <td><code class="csharp">Resolution.Minute</code><code class="python">Resolution.MINUTE</code></td>
            </tr>
            <tr>
                <td><code class="csharp">dataNormalizationMode</code><code class="python">data_normalization_mode</code></td>
                <td><code class="csharp">DataNormalizationMode.Adjusted</code><code class="python">DataNormalizationMode.ADJUSTED</code></td>
            </tr>
            <tr>
                <td><code class="csharp">dataMappingMode</code><code class="python">data_mapping_mode</code></td>
                <td><code>DataMappingMode.OpenInterest</code></td>
            </tr>
            <tr>
                <td><code class="csharp">contractDepthOffset</code><code class="python">contract_depth_offset</code></td>
                <td>0</td>
            </tr>
        </tbody>
    </table>
</ol>
