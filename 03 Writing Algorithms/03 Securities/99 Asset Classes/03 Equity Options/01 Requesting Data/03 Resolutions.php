<?php echo file_get_contents(DOCS_RESOURCES."/securities/resolutions/equity-options.html"); ?>


<p>The default resolution for Option contract subscriptions is <code class="csharp">Resolution.Minute</code><code class="python">Resolution.MINUTE</code>. To change the resolution, pass a <code>resolution</code> argument to the <code class="csharp">AddOptionContract</code><code class="python">add_option_contract</code>  method.</p>

<div class="section-example-container">
    <pre class="csharp">AddOptionContract(_contractSymbol, Resolution.Minute);</pre>
    <pre class="python">self.add_option_contract(self._contract_symbol, Resolution.MINUTE)</pre>
</div>

<p>To create custom resolution periods, see <a href="/docs/v2/writing-algorithms/consolidating-data/getting-started">Consolidating Data</a>.</p>
