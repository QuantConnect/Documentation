<p>Crypto Futures are currently only available on <code>Market.Binance</code>. To set the market for a security, pass a <code>market</code> argument to the <code class="csharp">AddCryptoFuture</code><code class="python">add_crypto_future</code>  method.</p>

<div class="section-example-container">
    <pre class="csharp">_symbol = AddCryptoFuture("BTCUSD", market: Market.Binance).Symbol;</pre>
    <pre class="python">self._symbol = self.add_crypto_future("BTCUSD", market=Market.BINANCE).symbol</pre>
</div>

<?php echo file_get_contents(DOCS_RESOURCES."/securities/supported-markets.html"); ?>
