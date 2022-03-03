<p>The US Equities data feed enables you to create a dynamic universe of securities.</p>

<h4>Coarse-Fine Universe</h4>
<p>The live data for coarse and fine universe selection arrives at 7 AM Eastern Standard Time (EST), so coarse and fine universe selection runs for live algorithms between 7 and 8 AM EST. This timing allows you to place trades before the market opens. Don't schedule anything for midnight because the universe selection data isn't ready yet.</p>
<div class="section-example-container">
    <pre class="csharp">AddUniverse(SelectCoarse, SelectFine);</pre>
    <pre class="python">self.AddUniverse(self.SelectCoarse, self.SelectFine)</pre>
</div>

<h4>ETF Constituent Universe</h4>
<p>The US Equities data feed enables you to create a universe of securities to match the constituents of an ETF. For more information about ETF universes, see the <a href='/datasets/quantconnect-us-etf-constituents'>US ETF Constituents dataset listing</a>.</p>

<div class="section-example-container">
    <pre class="csharp">var spy = AddEquity("SPY").Symbol;
AddUniverse(Universe.ETF(spy, UniverseSettings, ETFConstituentsFilter));</pre>
    <pre class="python">spy = self.AddEquity("SPY").Symbol
self.AddUniverse(self.Universe.ETF(spy, self.UniverseSettings, self.ETFConstituentsFilter))</pre>
</div>