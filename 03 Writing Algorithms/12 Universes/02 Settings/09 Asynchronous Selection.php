<? include(DOCS_RESOURCES."/universes/settings/async.php"); ?> add the universe.</p> 

<div class="section-example-container">
    <pre class="csharp">// To speed up algorithm performance, enable asynchronous universe selection. 
// Only use this when the code is self-contained and stateless.
UniverseSettings.Asynchronous = true;
AddUniverse(Universe.Top(50));</pre>
    <pre class="python"># To speed up algorithm performance, enable asynchronous universe selection. 
// Only use this when the code is self-contained and stateless.
self.universe_settings.asynchronous = True
self.add_universe(self.universe.top(50))</pre>
</div>

