<?php echo file_get_contents(DOCS_RESOURCES."/enumerations/market-futureoption.html"); ?>

<p>To set the market for a security, pass a <code>market</code> argument to the <code>AddOptionContract</code> method.</p>

<div class="section-example-container">
    <pre class="csharp">AddOptionContract(self.contract_symbol, market: Market.COMEX);</pre>
    <pre class="python">self.AddOptionContract(self.contract_symbol, market=Market.COMEX)</pre>
</div>

<?php echo file_get_contents(DOCS_RESOURCES."/securities/supported-markets.html"); ?>
