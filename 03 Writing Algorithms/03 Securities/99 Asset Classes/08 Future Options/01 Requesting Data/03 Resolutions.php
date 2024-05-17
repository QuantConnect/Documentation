<?php echo file_get_contents(DOCS_RESOURCES."/securities/resolutions/future-options.html"); ?>

<p>There is only one resolution option, so you don't need to pass a <code>resolution</code> argument to the <code class="csharp">AddFutureOptionContract</code><code class="python">add_future_option_contract</code>  method.</p>

<div class="section-example-container">
    <pre class="csharp">AddFutureOptionContract(_optionContractSymbol, Resolution.Minute);</pre>
    <pre class="python">self.add_future_option_contract(self._option_contract_symbol, Resolution.MINUTE)</pre>
</div>

<p>To create custom resolution periods, see <a href="/docs/v2/writing-algorithms/consolidating-data/getting-started">Consolidating Data</a>.</p>
