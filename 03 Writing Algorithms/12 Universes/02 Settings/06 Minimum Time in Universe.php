<? include(DOCS_RESOURCES."/universes/settings/minimum-time-in-universe.php"); ?> add the universe.</p> 

<div class="section-example-container">
    <pre class="csharp">UniverseSettings.MinimumTimeInUniverse = TimeSpan.FromDays(7);
AddUniverse(Universe.DollarVolume.Top(50));</pre>
    <pre class="python">self.UniverseSettings.MinimumTimeInUniverse = timedelta(7)
self.AddUniverse(self.Universe.DollarVolume.Top(50))</pre>
</div>

