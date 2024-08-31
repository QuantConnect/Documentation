<? include(DOCS_RESOURCES."/universes/settings/fill-forward.php"); ?> add the universe.</p> 

<div class="section-example-container">
    <pre class="csharp">// Disable fill-forward data before creating the universe (it's enabled by default). 
UniverseSettings.FillForward = false;
// Add the 50 Equities to the universe that have the most dollar trading volume.
AddUniverse(Universe.DollarVolume.Top(50));</pre>
    <pre class="python"># Disable fill-forward data before creating the universe (it's enabled by default).
self.universe_settings.fill_forward = False
# Add the 50 Equities to the universe that have the most dollar trading volume.
self.add_universe(self.universe.dollar_volume.top(50))</pre>
</div>

<p>To disable fill forward in derivative universes, pass a <code class="csharp">fillForward</code><code class="python">fill_forward</code> argument to the universe creation method.</p>
<div class="section-example-container">
    <pre class="csharp">// Disable fill-forward in the VIX Index Option universe.
AddIndexOption("VIX", fillForward: false);</pre>
    <pre class="python"># Disable fill-forward in the VIX Index Option universe.
self.add_index_option("VIX", fill_forward=False)</pre>
</div>
