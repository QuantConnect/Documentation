<? include(DOCS_RESOURCES."/universes/settings/resolution.php"); ?> pass a <code>resolution</code> argument to the universe creation method.</p> 

<div class="section-example-container">
    <pre class="csharp">// Add daily Equity Option data for the SPY.
AddOption("SPY", resolution: Resolution.Daily);</pre>
    <pre class="python"># Add daily Equity Option data for the SPY.
self.add_option("SPY", resolution=Resolution.DAILY)</pre>
</div>
