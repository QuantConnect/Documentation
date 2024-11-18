<? include(DOCS_RESOURCES."/universes/settings/leverage.php"); ?> add the universe.</p> 

<div class="section-example-container">
    <pre class="csharp">// Assign 2x leverage for all securities in the universe. 
UniverseSettings.Leverage = 2.0m;
// Add a universe of the 50 most liquid US Equities.
AddUniverse(Universe.DollarVolume.Top(50));</pre>
    <pre class="python"># Assign 2x leverage for all securities in the universe.  
self.universe_settings.leverage = 2.0
# Add a universe of the 50 most liquid US Equities.
self.add_universe(self.universe.dollar_volume.top(50))</pre>
</div>
