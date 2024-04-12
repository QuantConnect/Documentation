<? include(DOCS_RESOURCES."/universes/settings/leverage.php"); ?> add the universe.</p> 

<div class="section-example-container">
    <pre class="csharp">UniverseSettings.Leverage = 2.0m;
AddUniverse(Universe.DollarVolume.Top(50));</pre>
    <pre class="python">self.universe_settings.leverage = 2.0
self.add_universe(self.universe.dollar_volume.top(50))</pre>
</div>
