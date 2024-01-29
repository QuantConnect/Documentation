<? include(DOCS_RESOURCES."/universes/settings/contract-depth-offset.php"); ?> adjust the algorithm's <code>UniverseSettings</code> before you create the Universe Selection model.</p>

<div class="section-example-container">
	<pre class="csharp">UniverseSettings.ContractDepthOffset = 1;
AddUniverseSelection(
    new FutureUniverseSelectionModel(
        TimeSpan.FromDays(1), 
        _ => new List&lt;Symbol&gt; {{ QuantConnect.Symbol.Create(Futures.Indices.SP500EMini, SecurityType.Future, Market.CME) }}
    )
);</pre>
	<pre class="python">from Selection.FutureUniverseSelectionModel import FutureUniverseSelectionModel

self.UniverseSettings.ContractDepthOffset = 1
self.AddUniverseSelection(
    FutureUniverseSelectionModel(
        timedelta(1), 
        lambda _: [Symbol.Create(Futures.Indices.SP500EMini, SecurityType.Future, Market.CME)]
    )
)</pre>
</div>