<? include(DOCS_RESOURCES."/universes/settings/contract-depth-offset.php"); ?> pass a <code>contractDepthOffset</code> argument to the <code>AddFuture</code> method.</p>

<div class="section-example-container">
    <pre class="csharp">AddFuture(Futures.Currencies.BTC, contractDepthOffset: 3);</pre>
    <pre class="python">self.AddFuture(Futures.Currencies.BTC, contractDepthOffset=3)</pre>
</div>