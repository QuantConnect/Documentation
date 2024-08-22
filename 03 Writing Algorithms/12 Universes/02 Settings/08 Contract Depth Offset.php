<? include(DOCS_RESOURCES."/universes/settings/contract-depth-offset.php"); ?> pass a <code class="csharp">contractDepthOffset</code><code class="python">contract_depth_offset</code> argument to the <code class="csharp">AddFuture</code><code class="python">add_future</code> method.</p>

<div class="section-example-container">
    <pre class="csharp">// To adjust the contract depth offset to the second back month contract, pass the value 3 to the contractDepthOffset argument in the AddFuture method.
AddFuture(Futures.Currencies.BTC, contractDepthOffset: 3);</pre>
    <pre class="python"># To adjust the contract depth offset to the second back month contract, pass the value 3 to the contract_depth_offset argument in the add_future method.
self.add_future(Futures.Currencies.BTC, c=3)</pre>
</div>