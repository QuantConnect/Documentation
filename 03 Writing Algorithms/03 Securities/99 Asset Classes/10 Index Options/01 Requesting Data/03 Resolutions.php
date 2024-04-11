<?php echo file_get_contents(DOCS_RESOURCES."/securities/resolutions/index-options.html"); ?>


<p>The default resolution for Index Option subscriptions is <code>Resolution.Minute</code>. To change the resolution, pass a <code>resolution</code> argument to the <code>AddIndexOptionContract</code> method.</p>


<div class="section-example-container">
    <pre class="csharp">AddIndexOptionContract(_contractSymbol, Resolution.Hour);</pre>
    <pre class="python">self.add_index_option_contract(self.contract_symbol, Resolution.hour)</pre>
</div>

<p>To create custom resolution periods, see <a href="/docs/v2/writing-algorithms/consolidating-data/getting-started">Consolidating Data</a>.</p>