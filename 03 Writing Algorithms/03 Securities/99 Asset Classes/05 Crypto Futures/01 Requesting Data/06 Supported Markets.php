<p>Crypto Futures are currently only available on <code>Market.Binance</code>. To set the market for a security, pass a <code>market</code> argument to the <code>AddCryptoFuture</code> method.</p>

<div class="section-example-container">
    <pre class="csharp">_symbol = AddCryptoFuture("BTCUSD", market: Market.Binance).Symbol;</pre>
    <pre class="python">self.symbol = self.add_crypto_future("BTCUSD", market=Market.binance).symbol</pre>
</div>

<?php echo file_get_contents(DOCS_RESOURCES."/securities/supported-markets.html"); ?>
