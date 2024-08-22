<? include(DOCS_RESOURCES."/universes/settings/fill-forward.php"); ?> add the universe.</p> 

<div class="section-example-container">
    <pre class="csharp">// Disable fill forward in non-derivative universe by adjusting the FillForward property in UniverseSettings object.
UniverseSettings.FillForward = false;
AddUniverse(Universe.DollarVolume.Top(50));</pre>
    <pre class="python"># Disable fill forward in non-derivative universe by adjusting the fill_forward field in universe_settings object.
self.universe_settings.fill_forward = False
self.add_universe(self.universe.dollar_volume.top(50))</pre>
</div>

<p>To disable fill forward in derivative universes, pass a <code class="csharp">fillForward</code><code class="python">fill_forward</code> argument to the universe creation method.</p>
<div class="section-example-container">
    <pre class="csharp">// Disable fill forward in derivative universe by passing fillForward argument to the AddIndexOption method.
AddIndexOption("VIX", fillForward: false);</pre>
    <pre class="python"># Disable fill forward in derivative universe by passing fill_forward argument to the add_index_option method.
self.add_index_option("VIX", fill_forward=False)</pre>
</div>
