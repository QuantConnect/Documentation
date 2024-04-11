<? include(DOCS_RESOURCES."/universes/settings/leverage.php"); ?> create the Universe Selection model.</p> 

<div class="section-example-container">
    <pre class="csharp">UniverseSettings.Leverage = 2.0m;
AddUniverseSelection(new EmaCrossUniverseSelectionModel());</pre>
    <pre class="python">self.universe_settings.leverage = 2.0
self.add_universe_selection(EmaCrossUniverseSelectionModel())</pre>
</div>
