<? include(DOCS_RESOURCES."/universes/settings/resolution.php"); ?> pass a <code>resolution</code> argument to the universe creation method.</p> 

<div class="section-example-container">
    <pre class="csharp">// Change the resolution to daily on SPY by passing it as an argument in AddOption function.
AddOption("SPY", resolution: Resolution.Daily);
    </pre>
    <pre class="python"># Change the resolution to daily on SPY by passing it as an argument in add_option function.
self.add_option("SPY", resolution=Resolution.DAILY)
    </pre>
</div>
