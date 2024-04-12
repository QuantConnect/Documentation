<? include(DOCS_RESOURCES."/universes/settings/fill-forward.php"); ?> create the Universe Selection model.</p> 

<div class="section-example-container">
	<pre class="csharp">UniverseSettings.FillForward = false;
AddUniverseSelection(
    new OptionUniverseSelectionModel(
        TimeSpan.FromDays(1), 
        _ => new [] { QuantConnect.Symbol.Create("SPY", SecurityType.Option, Market.USA) }
    )
);</pre>
	<pre class="python">from Selection.optionUniverseSelectionModel import OptionUniverseSelectionModel 

self.universe_settings.fill_forward = False
self.add_universe_selection(
    OptionUniverseSelectionModel(
        timedelta(1), lambda _: [Symbol.create("SPY", SecurityType.OPTION, Market.USA)]
    )
)</pre>
</div>