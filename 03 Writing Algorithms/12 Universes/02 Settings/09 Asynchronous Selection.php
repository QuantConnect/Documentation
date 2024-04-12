<? include(DOCS_RESOURCES."/universes/settings/async.php"); ?> add the universe.</p> 

<div class="section-example-container">
    <pre class="csharp">UniverseSettings.Asynchronous = true;
AddUniverse(Universe.DollarVolume.Top(50));</pre>
    <pre class="python">self.universe_settings.asynchronous = True
self.add_universe(self.universe.dollar_volume.top(50))</pre>
</div>

