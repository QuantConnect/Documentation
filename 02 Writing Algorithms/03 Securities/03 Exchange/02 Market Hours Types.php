<p>By default, your security subscriptions only cover regular trading hours. To subscribe to pre- and post-market trading hours for a specific asset, enable the <code>extendedMarketHours</code> argument when you create the security subscription.</p>

<div class="section-example-container">
    <pre class="csharp">AddEquity("SPY", extendedMarketHours: true);</pre>
    <pre class="python">self.AddEquity("SPY", extendedMarketHours=True)</pre>
</div>

<p>To subscribe to pre- and post-market trading hours for all assets, enable the <code>ExtendedMarketHours</code> <a href='/docs/v2/writing-algorithms/universes/key-concepts#05-Universe-Settings'>universe setting</a> before you create the security subscriptions.</p>

<div class="section-example-container">
    <pre class="csharp">UniverseSettings.ExtendedMarketHours = true;</pre>
    <pre class="python">self.UniverseSettings.ExtendedMarketHours = True</pre>
</div>