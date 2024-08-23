<? include(DOCS_RESOURCES."/universes/settings/extended-market-hours.php"); ?> 

<p>To enable extended market hours in non-derivative universes, in the <a href='/docs/v2/writing-algorithms/initialization'>Initialize</a> method, adjust the algorithm's <code class="csharp">UniverseSettings</code><code class="python">universe_settings</code> before you add the universe.</p> 

<div class="section-example-container">
    <pre class="csharp"> // Enables pre and post market hours data assets in the universe. 
UniverseSettings.ExtendedMarketHours = true;
// Adds securities to universe that have top 50 highest dollar trading volume.
AddUniverse(Universe.DollarVolume.Top(50));</pre>
    <pre class="python"> # Enables pre and post market hours data assets in the universe.
self.universe_settings.extended_market_hours = True
# Adds securities to universe that have top 50 highest dollar trading volume.
self.add_universe(self.universe.dollar_volume.top(50))</pre>
</div>

<p>To enable extended market hours in derivative universes, pass an <code class="csharp">extendedMarketHours</code><code class="python">extended_market_hours</code> argument to the universe creation method.</p> 

<div class="section-example-container">
    <pre class="csharp"> // Requesting pre and post market hours data for the specific asset.
AddFuture(Futures.Currencies.BTC, extendedMarketHours: true);</pre>
    <pre class="python"> # Requesting pre and post market hours data for the specific asset.
self.add_future(Futures.Currencies.BTC, extended_market_hours=True)</pre>
</div>
