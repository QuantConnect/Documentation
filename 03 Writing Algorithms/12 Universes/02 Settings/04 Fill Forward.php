<? include(DOCS_RESOURCES."/universes/settings/fill-forward.php"); ?> add the universe.</p> 

<div class="section-example-container">
    <pre class="csharp">UniverseSettings.FillForward = false;
AddUniverse(Universe.DollarVolume.Top(50));</pre>
    <pre class="python">self.universe_settings.fill_forward = False
self.add_universe(self.universe.dollar_volume.top(50))</pre>
</div>

<p>To disable fill forward in derivative universes, pass a <code>fillForward</code> argument to the universe creation method.</p>
<div class="section-example-container">
    <pre class="csharp">AddIndexOption("VIX", fillForward: false);</pre>
    <pre class="python">self.add_index_option("VIX", fillForward=False)</pre>
</div>
