<? include(DOCS_RESOURCES."/universes/settings/contract-depth-offset.php"); ?> pass a <code>contractDepthOffset</code> argument to the <code class="csharp">AddFuture</code><code class="python">add_future</code> method.</p>

<div class="section-example-container">
    <pre class="csharp">AddFuture(Futures.Currencies.BTC, contractDepthOffset: 3);</pre>
    <pre class="python">self.add_future(Futures.Currencies.BTC, contract_depth_offset=3)</pre>
</div>