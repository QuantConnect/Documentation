<p>The only market available for Forex pairs is <code>Market.Oanda</code>, so you don't need to pass a <code>market</code> argument to the <code>AddForex</code> method.</p>

<div class="section-example-container">
    <pre class="csharp">_symbol = AddForex("EURUSD", market: Market.Oanda).Symbol;</pre>
    <pre class="python">self.symbol = self.add_forex("EURUSD", market=Market.OANDA).symbol</pre>
</div>

<?php echo file_get_contents(DOCS_RESOURCES."/securities/supported-markets.html"); ?>
