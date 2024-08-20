<? include(DOCS_RESOURCES."/universes/settings/minimum-time-in-universe.php"); ?> add the universe.</p> 

<div class="section-example-container">
    <pre class="csharp">\\ Each security in the universe will remain in the securities list for 7 days before being removed.
        UniverseSettings.MinimumTimeInUniverse = TimeSpan.FromDays(7);
AddUniverse(Universe.DollarVolume.Top(50));</pre>
    <pre class="python"># Each security in the universe will remain in the securities list for 7 days before being removed.
        self.universe_settings.minimum_time_in_universe = timedelta(7)
self.add_universe(self.universe.dollar_volume.top(50))</pre>
</div>

