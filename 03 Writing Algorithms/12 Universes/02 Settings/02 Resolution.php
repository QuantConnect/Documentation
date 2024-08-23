<? include(DOCS_RESOURCES."/universes/settings/resolution.php"); ?> pass a <code>resolution</code> argument to the universe creation method.</p> 

<div class="section-example-container">
    <pre class="csharp"> // Adding option data for equity "SPY" with Daily pricing Resolution.
AddOption("SPY", resolution: Resolution.Daily);</pre>
    <pre class="python"> # Adding option data for equity "SPY" with Daily pricing Resolution.
self.add_option("SPY", resolution=Resolution.DAILY)</pre>
</div>
