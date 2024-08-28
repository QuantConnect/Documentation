<? include(DOCS_RESOURCES."/universes/settings/fill-forward.php"); ?> create the Universe Selection model.</p> 

<div class="section-example-container">
	<pre class="csharp">// Disable fill forward to ensure each day's data is used exclusively, preventing previous day's data from influencing decisions and select options for SPY with a daily update interval to track specific option contracts.
UniverseSettings.FillForward = false;
AddUniverseSelection(
    new OptionUniverseSelectionModel(
        TimeSpan.FromDays(1), 
        _ => new [] { QuantConnect.Symbol.Create("SPY", SecurityType.Option, Market.USA) }
    )
);</pre>
	<pre class="python"># Disable fill forward to ensure each day's data is used exclusively, preventing previous day's data from influencing decisions and select options for SPY with a daily update interval to track specific option contracts.
from Selection.OptionUniverseSelectionModel import OptionUniverseSelectionModel 

self.universe_settings.fill_forward = False
self.add_universe_selection(
    OptionUniverseSelectionModel(
        timedelta(1), lambda _: [Symbol.create("SPY", SecurityType.OPTION, Market.USA)]
    )
)</pre>
</div>