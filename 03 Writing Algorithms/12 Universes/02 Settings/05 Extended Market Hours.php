<? include(DOCS_RESOURCES."/universes/settings/extended-market-hours.php"); ?> 

<p>To enable extended market hours in non-derivative universes, in the <a href='/docs/v2/writing-algorithms/initialization'>Initialize</a> method, adjust the algorithm's <code>UniverseSettings</code> before you add the universe.</p> 

<div class="section-example-container">
    <pre class="csharp">UniverseSettings.ExtendedMarketHours = true;
AddUniverse(Universe.DollarVolume.Top(50));</pre>
    <pre class="python">self.UniverseSettings.ExtendedMarketHours = True
self.AddUniverse(self.Universe.DollarVolume.Top(50))</pre>
</div>

<p>To enable extended market hours in derivative universes, pass an <code>extendedMarketHours</code> argument to the universe creation method.</p> 

<div class="section-example-container">
    <pre class="csharp">AddFuture(Futures.Currencies.BTC, extendedMarketHours: true);</pre>
    <pre class="python">self.AddUniverse(Futures.Currencies.BTC, extendedMarketHours=True)</pre>
</div>