<? include(DOCS_RESOURCES."/universes/settings/fill-forward.php"); ?> create the Universe Selection model.</p> 

<div class="section-example-container">
	<pre class="csharp">UniverseSettings.FillForward = false;
AddUniverseSelection(
    new OptionUniverseSelectionModel(
        TimeSpan.FromDays(1), 
        _ => new [] { QuantConnect.Symbol.Create("SPY", SecurityType.Option, Market.USA) }
    )
);</pre>
	<pre class="python">from Selection.OptionUniverseSelectionModel import OptionUniverseSelectionModel 

self.UniverseSettings.FillForward = False
self.AddUniverseSelection(
    OptionUniverseSelectionModel(
        timedelta(1), lambda _: [Symbol.Create("SPY", SecurityType.Option, Market.USA)]
    )
)</pre>
</div>