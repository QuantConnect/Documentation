<? include(DOCS_RESOURCES."/universes/settings/extended-market-hours.php"); ?> 

<p>To enable extended market hours in non-derivative universes, in the <a href='/docs/v2/writing-algorithms/initialization'>Initialize</a> method, adjust the algorithm's <code class="csharp">UniverseSettings</code><code class="python">universe_settings</code> before you add the universe.</p> 

<div class="section-example-container">
    <pre class="csharp"> // Enables extended market hour data for non-derivative universes (only for intraday resolutions). 
UniverseSettings.ExtendedMarketHours = true;
// Adds securities to universe that have top 50 highest dollar trading volume.
AddUniverse(Universe.DollarVolume.Top(50));</pre>
    <pre class="python"> # Enables extended market hour data for non-derivative universes (only for intraday resolutions). 
self.universe_settings.extended_market_hours = True
# Adds securities to universe that have top 50 highest dollar trading volume.
self.add_universe(self.universe.dollar_volume.top(50))</pre>
</div>

<p>To enable extended market hours in derivative universes, pass an <code class="csharp">extendedMarketHours</code><code class="python">extended_market_hours</code> argument to the universe creation method.</p> 

<div class="section-example-container">
    <pre class="csharp"> // Enabling extended market hours data in derivative universes.
AddFuture(Futures.Currencies.BTC, extendedMarketHours: true);</pre>
    <pre class="python"> # Enabling extended market hours data in derivative universes.
self.add_future(Futures.Currencies.BTC, extended_market_hours=True)</pre>
</div>