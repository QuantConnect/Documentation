<? include(DOCS_RESOURCES."/universes/settings/extended-market-hours.php"); ?> 

<p>To enable extended market hours in non-derivative universes, in the <a href='/docs/v2/writing-algorithms/initialization'>Initialize</a> method, adjust the algorithm's <code>UniverseSettings</code> before you add the universe.</p> 

<div class="section-example-container">
    <pre class="csharp">UniverseSettings.ExtendedMarketHours = true;
AddUniverse(Universe.DollarVolume.Top(50));</pre>
    <pre class="python">self.universe_settings.extended_market_hours = True
self.add_universe(self.universe.dollar_volume.top(50))</pre>
</div>

<p>To enable extended market hours in derivative universes, pass an <code class="csharp">extendedMarketHours</code><code class="python">extended_market_hours</code> argument to the universe creation method.</p> 

<div class="section-example-container">
    <pre class="csharp">AddFuture(Futures.Currencies.BTC, extendedMarketHours: true);</pre>
    <pre class="python">self.add_universe(Futures.Currencies.BTC, extended_market_hours=True)</pre>
</div>