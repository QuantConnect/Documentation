<?php echo file_get_contents(DOCS_RESOURCES."/securities/resolutions/equity-options.html"); ?>


<p>The default resolution for Option contract subscriptions is <code>Resolution.Minute</code>. To change the resolution, pass a <code>resolution</code> argument to the <code>AddOptionContract</code> method.</p>

<div class="section-example-container">
    <pre class="csharp">AddOptionContract(_contractSymbol, Resolution.Minute);</pre>
    <pre class="python">self.add_option_contract(self.contract_symbol, Resolution.minute)</pre>
</div>

<p>To create custom resolution periods, see <a href="/docs/v2/writing-algorithms/consolidating-data/getting-started">Consolidating Data</a>.</p>
