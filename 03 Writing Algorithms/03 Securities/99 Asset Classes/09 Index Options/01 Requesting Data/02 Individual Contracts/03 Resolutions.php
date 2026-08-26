<?php echo file_get_contents(DOCS_RESOURCES."/securities/resolutions/index-options.html"); ?>


<p>The default resolution for Index Option subscriptions is <code class="csharp">Resolution.Minute</code><code class="python">Resolution.MINUTE</code>. To change the resolution, pass a <code>resolution</code> argument to the <code class="csharp">AddIndexOptionContract</code><code class="python">add_index_option_contract</code> method.</p>


<div class="section-example-container">
    <pre class="csharp">AddIndexOptionContract(_contractSymbol, Resolution.Hour);</pre>
    <pre class="python">self.add_index_option_contract(self._contract_symbol, Resolution.HOUR)</pre>
</div>

<?php
$csharpSubscription = 'AddIndexOptionContract(_contractSymbol, resolution);';
$pythonSubscription = 'self.add_index_option_contract(self._contract_symbol, resolution)';
include(DOCS_RESOURCES."/securities/resolutions/options-live-second.php");
?>

<p>To create custom resolution periods, see <a href="/docs/v2/writing-algorithms/consolidating-data/getting-started">Consolidating Data</a>.</p>
