<? include(DOCS_RESOURCES."/universes/settings/leverage.php"); ?> add the universe.</p> 

<div class="section-example-container">
    <pre class="csharp">// Assign 2x leverage for all securities in universe. 
UniverseSettings.Leverage = 2.0m;
// Add the 50 Equities to the universe that have the most dollar trading volume.
AddUniverse(Universe.DollarVolume.Top(50));</pre>
    <pre class="python"># Assign 2x leverage for all securities in universe.  
self.universe_settings.leverage = 2.0
# Add the 50 Equities to the universe that have the most dollar trading volume.
self.add_universe(self.universe.dollar_volume.top(50))</pre>
</div>
