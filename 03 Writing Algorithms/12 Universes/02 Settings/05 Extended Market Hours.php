<? include(DOCS_RESOURCES."/universes/settings/extended-market-hours.php"); ?> 

<p>To enable extended market hours in non-derivative universes, in the <a href='/docs/v2/writing-algorithms/initialization'>Initialize</a> method, adjust the algorithm's <code class="csharp">UniverseSettings</code><code class="python">universe_settings</code> before you add the universe.</p> 

<div class="section-example-container">
    <pre class="csharp">// Enable pre- and post-market hours data for assets in the universe. 
UniverseSettings.ExtendedMarketHours = true;
// Add the 50 Equities to the universe that have the most dollar trading volume.
AddUniverse(Universe.DollarVolume.Top(50));</pre>
    <pre class="python"># Enable pre- and post-market hours data for assets in the universe.
self.universe_settings.extended_market_hours = True
# Add the 50 Equities to the universe that have the most dollar trading volume.
self.add_universe(self.universe.dollar_volume.top(50))</pre>
</div>

<p>To enable extended market hours in derivative universes, pass an <code class="csharp">extendedMarketHours</code><code class="python">extended_market_hours</code> argument to the universe creation method.</p> 

<div class="section-example-container">
    <pre class="csharp">// Request pre- and post-market hours data for a Futures universe.
AddFuture(Futures.Currencies.BTC, extendedMarketHours: true);</pre>
    <pre class="python"># Request pre- and post-market hours data for a Futures universe.
self.add_future(Futures.Currencies.BTC, extended_market_hours=True)</pre>
</div>
