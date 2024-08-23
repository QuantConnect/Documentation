<? include(DOCS_RESOURCES."/universes/settings/minimum-time-in-universe.php"); ?> add the universe.</p> 

<div class="section-example-container">
    <pre class="csharp">// Keep each security in universe for a minimum of 7 days.
UniverseSettings.MinimumTimeInUniverse = TimeSpan.FromDays(7);
// Adds securities to universe that have top 50 highest dollar trading volume.
AddUniverse(Universe.DollarVolume.Top(50));</pre>
    <pre class="python"># Keep each security in universe for a minimum of 7 days.
self.universe_settings.minimum_time_in_universe = timedelta(7)
# Adds securities to universe that have top 50 highest dollar trading volume.
self.add_universe(self.universe.dollar_volume.top(50))</pre>
</div>

