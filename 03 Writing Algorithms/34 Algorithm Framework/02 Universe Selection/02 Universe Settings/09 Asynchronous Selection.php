<? include(DOCS_RESOURCES."/universes/settings/async.php"); ?> create the Universe Selection model.</p> 

<div class="section-example-container">
    <pre class="csharp">// Run universe selection asynchronously to speed up your algorithm.
UniverseSettings.Asynchronous = true;
AddUniverseSelection(new EmaCrossUniverseSelectionModel());</pre>
    <pre class="python"># Run universe selection asynchronously to speed up your algorithm.
self.universe_settings.asynchronous = True
self.add_universe_selection(EmaCrossUniverseSelectionModel())</pre>
</div>

