<? include(DOCS_RESOURCES."/universes/settings/async.php"); ?> add the universe.</p> 

<div class="section-example-container">
    <pre class="csharp">// To speed up algorithm performance you can set universe selection to happen asynchronously. Only use this when the code is self-contained and stateless.
UniverseSettings.Asynchronous = true;
AddUniverse(Universe.DollarVolume.Top(50));</pre>
    <pre class="python"># To speed up algorithm performance you can set universe selection to happen asynchronously. Only use this when the code is self-contained and stateless.
self.universe_settings.asynchronous = True
self.add_universe(self.universe.dollar_volume.top(50))</pre>
</div>

