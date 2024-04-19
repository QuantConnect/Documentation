<?php echo file_get_contents(DOCS_RESOURCES."/securities/resolutions/futures.html"); ?>

<p>The default resolution for Futures contract subscriptions is <code>Resolution.Minute</code>. To change the resolution, pass a <code>resolution</code> argument to the <code class="csharp">AddFutureContract</code><code class="python">add_future_contract</code> method.</p>

<div class="section-example-container">
    <pre class="csharp">AddFutureContract(_contractSymbol, Resolution.Daily);</pre>
    <pre class="python">self.add_future_contract(self.contract_symbol, Resolution.DAILY)</pre>
</div>

<p>To create custom resolution periods, see <a href="/docs/v2/writing-algorithms/consolidating-data/getting-started">Consolidating Data</a>.</p>
