<? include(DOCS_RESOURCES."/universes/settings/resolution.php"); ?> adjust the algorithm's <code>UniverseSettings</code> before you create the Universe Selection model.</p> 

<div class="section-example-container">
    <pre class="csharp">UniverseSettings.Resolution = Resolution.Daily;
AddUniverseSelection(new ETFConstituentsUniverseSelectionModel("SPY"));</pre>
    <pre class="python">self.UniverseSettings.Resolution = Resolution.Daily
self.AddUniverseSelection(ETFConstituentsUniverseSelectionModel("SPY"))</pre>
</div>
