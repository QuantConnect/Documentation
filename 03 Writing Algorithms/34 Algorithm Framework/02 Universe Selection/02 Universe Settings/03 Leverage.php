<? include(DOCS_RESOURCES."/universes/settings/leverage.php"); ?> create the Universe Selection model.</p> 

<div class="section-example-container">
    <pre class="csharp">// Set unviverse leverage to 2x to increase potential returns from each asset in the non-derivative universe to increase potential returns.
UniverseSettings.Leverage = 2.0m;
// Select universe based on EMA cross signals to identify and trade assets with trending behaviors.
AddUniverseSelection(new EmaCrossUniverseSelectionModel());</pre>
    <pre class="python"># Set unviverse leverage to 2x to increase potential returns from each asset in the non-derivative universe to increase potential returns.
self.universe_settings.leverage = 2.0
# Select universe based on EMA cross signals to identify and trade assets with trending behaviors.
self.add_universe_selection(EmaCrossUniverseSelectionModel())</pre>
</div>
