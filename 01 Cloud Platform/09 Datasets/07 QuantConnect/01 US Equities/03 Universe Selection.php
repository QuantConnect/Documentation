<p>The QuantConnect data provider enables you to create a dynamic universe of US Equities.</p>

<h4>Fundamental Universe</h4>
<? include(DOCS_RESOURCES."/data-feeds/us-equities/fundamental-data-availability.html"); ?>
<div class="section-example-container">
    <pre class="csharp">// Run universe selection asynchronously to speed up your algorithm.
UniverseSettings.Asynchronous = true;
AddUniverse(SelectFundamental);</pre>
    <pre class="python">#  Run universe selection asynchronously to speed up your algorithm.
self.universe_settings.asynchronous = True
self.add_universe(self._select_fundamental)</pre>
</div>

<h4>ETF Constituent Universe</h4>
<p>The QuantConnect data provider enables you to create a universe of securities to match the constituents of an ETF. For more information about ETF universes, see <a href='/docs/v2/writing-algorithms/universes/equity/etf-constituents-universes'>ETF Constituents Selection</a>.</p>

<div class="section-example-container">
    <pre class="csharp">// Request an async universe of stocks that match the ETF constituents of SPY
UniverseSettings.Asynchronous = true;
var spy = AddEquity("SPY").Symbol;
AddUniverse(Universe.ETF(spy, UniverseSettings, ETFConstituentsFilter));</pre>
    <pre class="python"># Request an async universe of stocks that match the ETF constituents of SPY
self.universe_settings.asynchronous = True
spy = self.add_equity("SPY").symbol
self.add_universe(self.universe.etf(spy, self.universe_settings, self._etf_constituents_filter))</pre>
</div>
