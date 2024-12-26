<? 
$pyVar = $writingAlgorithms ? "self" : "qb";
$pyFutureVar = $writingAlgorithms ? "self." : "";
$cVar = $writingAlgorithms ? "" : "qb.";
$envName = $writingAlgorithms ? "algorithm" : "notebook";
$cPrintMethod = $writingAlgorithms ? "Log" : "Console.WriteLine";
$pyPrintMethod = $writingAlgorithms ? "self.Log" : "print";
?>

<p>The <code class="csharp">History</code><code class="python">history</code> method accepts the following additional arguments:</p>

<table class='qc-table table'>
    <thead>
        <tr>
            <th>Argument</th>
            <th>Data Type</th>
            <th>Description</th>
            <th>Default Value</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><code class="csharp">fillForward</code><code class="python">fill_forward</code></td>
	        <td><code class='csharp'>bool?</code><code class='python'>bool/NoneType</code></td>
            <td>True to <a href='/docs/v2/writing-algorithms/securities/requesting-data#05-Fill-Forward'>fill forward</a> missing data. Otherwise, false. If you don't provide a value, it uses the fill forward mode of the security subscription.</td>
            <td><code class='csharp'>null</code><code class='python'>None</code></td>
        </tr>
        <tr>
            <td><code class="csharp">extendedMarketHours</code><code class="python">extended_market_hours</code></td>
	        <td><code class='csharp'>bool?</code><code class='python'>bool/NoneType</code></td>
            <td>True to include extended market hours data. Otherwise, false.</td>
            <td><code class='csharp'>null</code><code class='python'>None</code></td>
        </tr>
        <tr>
            <td><code class="csharp">dataMappingMode</code><code class="python">data_mapping_mode</code></td>
	        <td><code class='csharp'>DataMappingMode?</code><code class='python'>DataMappingMode/NoneType</code></td>
            <td>The <a href='/docs/v2/writing-algorithms/universes/futures#12-Continous-Contracts'>contract mapping mode</a> to use for the security history request.</td>
            <td><code class='csharp'>null</code><code class='python'>None</code></td>
        </tr>
        <tr>
            <td><code class="csharp">dataNormalizationMode</code><code class="python">data_normalization_mode</code></td>
            <td><code class='csharp'>DataNormalizationMode?</code><code class='python'>DataNormalizationMode/NoneType</code></td>
            <td>The price scaling mode to use for <a href='/docs/v2/writing-algorithms/securities/asset-classes/us-equity/requesting-data#11-Data-Normalization'>US Equities</a> or <a href='/docs/v2/writing-algorithms/universes/futures#12-Continous-Contracts'>continuous Futures contracts</a>. If you don't provide a value, it uses the data normalization mode of the security subscription.</td>
            <td><code class='csharp'>null</code><code class='python'>None</code></td>
        </tr>
        <tr>
            <td><code class="csharp">contractDepthOffset</code><code class="python">contract_depth_offset</code></td>
            <td><code class='csharp'>int?</code><code class='python'>int/NoneType</code></td>
            <td>The desired offset from the current front month for <a href='/docs/v2/writing-algorithms/universes/futures#12-Continous-Contracts'>continuous Futures contracts</a>.</td>
            <td><code class='csharp'>null</code><code class='python'>None</code></td>
        </tr>
    </tbody>
</table>

<div class='section-example-container'>
    <pre class='python'><?=$pyFutureVar?>future = <?=$pyVar?>.add_future(Futures.Indices.SP_500_E_MINI)
history = <?=$pyVar?>.history(
    symbols=[<?=$pyFutureVar?>future.symbol], 
    start=<?=$pyVar?>.time - timedelta(days=15), 
    end=<?=$pyVar?>.time, 
    resolution=Resolution.MINUTE, 
    fill_forward=False, 
    extended_market_hours=False, 
    data_mapping_mode=DataMappingMode.LAST_TRADING_DAY, 
    data_normalization_mode=DataNormalizationMode.RAW, 
    contract_depth_offset=0
)</pre>
    <pre class='csharp'>var future = <?=$cVar?>AddFuture(Futures.Indices.SP500EMini);
var history = <?=$cVar?>History(
    symbols: new[] {future.Symbol}, 
    start: <?=$cVar?>Time - TimeSpan.FromDays(15),
    end: <?=$cVar?>Time,
    resolution: Resolution.Minute,
    fillForward: false,
    extendedMarketHours: false,
    dataMappingMode: DataMappingMode.LastTradingDay,
    dataNormalizationMode: DataNormalizationMode.Raw,
    contractDepthOffset: 0);</pre>
</div>
