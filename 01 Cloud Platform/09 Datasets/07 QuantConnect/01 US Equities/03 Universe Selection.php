<p>The QuantConnect data provider enables you to create a dynamic universe of US Equities.</p>

<h4>Fundamental Universe</h4>
<? include(DOCS_RESOURCES."/data-feeds/us-equities/fundamental-data-availability.html"); ?>
<div class="section-example-container">
    <pre class="csharp">UniverseSettings.Asynchronous = true;
AddUniverse(SelectFundamental);</pre>
    <pre class="python">self.UniverseSettings.Asynchronous = True
self.AddUniverse(self.SelectFundamental)</pre>
</div>

<h4>ETF Constituent Universe</h4>
<p>The QuantConnect data provider enables you to create a universe of securities to match the constituents of an ETF. For more information about ETF universes, see <a href='/docs/v2/writing-algorithms/universes/equity/etf-constituents-universes'>ETF Constituents Selection</a>.</p>

<div class="section-example-container">
    <pre class="csharp">UniverseSettings.Asynchronous = true;
var spy = AddEquity("SPY").Symbol;
AddUniverse(Universe.ETF(spy, UniverseSettings, ETFConstituentsFilter));</pre>
    <pre class="python">self.UniverseSettings.Asynchronous = True
spy = self.AddEquity("SPY").Symbol
self.AddUniverse(self.Universe.ETF(spy, self.UniverseSettings, self.ETFConstituentsFilter))</pre>
</div>
