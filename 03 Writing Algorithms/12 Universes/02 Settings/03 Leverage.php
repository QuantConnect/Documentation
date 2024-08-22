<? include(DOCS_RESOURCES."/universes/settings/leverage.php"); ?> add the universe.</p> 

<div class="section-example-container">
    <pre class="csharp"> // Assign leverage to 2.0 for all securities in universe. 
UniverseSettings.Leverage = 2.0m;
// Adds securities to universe that have top 50 highest dollar trading volume.
AddUniverse(Universe.DollarVolume.Top(50));</pre>
    <pre class="python"> # Assign leverage to 2.0 for universe. 
self.universe_settings.leverage = 2.0
# Adds securities to universe that have top 50 highest dollar trading volume.
self.add_universe(self.universe.dollar_volume.top(50))</pre>
</div>
