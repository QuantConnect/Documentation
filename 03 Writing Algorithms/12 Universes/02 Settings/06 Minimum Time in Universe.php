<? include(DOCS_RESOURCES."/universes/settings/minimum-time-in-universe.php"); ?> add the universe.</p> 

<div class="section-example-container">
    <pre class="csharp">// Keep each security in the universe for a minimum of 7 days.
UniverseSettings.MinimumTimeInUniverse = TimeSpan.FromDays(7);
// Add the top 50 most liquid Equities to the universe.
AddUniverse(Universe.DollarVolume.Top(50));</pre>
    <pre class="python"># Keep each security in the universe for a minimum of 7 days.
self.universe_settings.minimum_time_in_universe = timedelta(7)
# Add the top 50 most liquid Equities to the universe.
self.add_universe(self.universe.dollar_volume.top(50))</pre>
</div>

