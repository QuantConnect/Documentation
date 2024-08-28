<? include(DOCS_RESOURCES."/universes/settings/resolution.php"); ?> adjust the algorithm's <code>UniverseSettings</code> before you create the Universe Selection model.</p> 

<div class="section-example-container">
    <pre class="csharp">// Set universe settings to subscribe to daily data and select SPY ETF constituents as the trading universe.
UniverseSettings.Resolution = Resolution.Daily;
AddUniverseSelection(new ETFConstituentsUniverseSelectionModel("SPY"));</pre>
    <pre class="python"># Use daily data and select SPY ETF constituents as the trading universe.
self.universe_settings.resolution = Resolution.DAILY
self.add_universe_selection(ETFConstituentsUniverseSelectionModel("SPY"))</pre>
</div>
