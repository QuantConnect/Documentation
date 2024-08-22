<? include(DOCS_RESOURCES."/universes/settings/leverage.php"); ?> add the universe.</p> 

<div class="section-example-container">
    <pre class="csharp">// Change the leverage value by adjusting the Leverage property in UniverseSettings.
UniverseSettings.Leverage = 2.0m;
AddUniverse(Universe.DollarVolume.Top(50));</pre>
    <pre class="python"># Change the leverage field by adjusting the leverage value in universe_settings.
self.universe_settings.leverage = 2.0
self.add_universe(self.universe.dollar_volume.top(50))
    </pre>
</div>
