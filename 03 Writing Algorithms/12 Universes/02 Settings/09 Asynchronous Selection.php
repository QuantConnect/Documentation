<? include(DOCS_RESOURCES."/universes/settings/async.php"); ?> add the universe.</p> 

<div class="section-example-container">
    <pre class="csharp">// To enable universe selection asynchronously, set the Asynchronous property in the UniverseSettings object to true.
UniverseSettings.Asynchronous = true;
AddUniverse(Universe.DollarVolume.Top(50));</pre>
    <pre class="python"># To enable universe selection asynchronously, set the asynchronous field in the universe_settings object to true.
self.universe_settings.asynchronous = True
self.add_universe(self.universe.dollar_volume.top(50))</pre>
</div>

