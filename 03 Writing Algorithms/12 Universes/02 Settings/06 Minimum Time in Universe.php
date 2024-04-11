<? include(DOCS_RESOURCES."/universes/settings/minimum-time-in-universe.php"); ?> add the universe.</p> 

<div class="section-example-container">
    <pre class="csharp">UniverseSettings.MinimumTimeInUniverse = TimeSpan.FromDays(7);
AddUniverse(Universe.DollarVolume.Top(50));</pre>
    <pre class="python">self.universe_settings.minimum_time_in_universe = timedelta(7)
self.add_universe(self.universe.dollar_volume.top(50))</pre>
</div>

