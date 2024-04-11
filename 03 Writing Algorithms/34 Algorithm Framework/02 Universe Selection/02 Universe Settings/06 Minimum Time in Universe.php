<? include(DOCS_RESOURCES."/universes/settings/minimum-time-in-universe.php"); ?> create the Universe Selection model.</p> 

<div class="section-example-container">
    <pre class="csharp">UniverseSettings.MinimumTimeInUniverse = TimeSpan.FromDays(7);
AddUniverseSelection(new ETFConstituentsUniverseSelectionModel("QQQ"));</pre>
    <pre class="python">self.universe_settings.minimum_time_in_universe = timedelta(7)
self.add_universe_selection(ETFConstituentsUniverseSelectionModel("QQQ"))</pre>
</div>

