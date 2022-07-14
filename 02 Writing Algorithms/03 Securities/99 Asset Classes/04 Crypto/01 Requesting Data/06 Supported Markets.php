<p>The following <code>Market</code> enumeration members are available for Crypto:</p>

<div data-tree='QuantConnect.Market' data-fields='Bitfinex,GDAX,Kraken,Binance,FTX,FTXUS,BinanceUS'></div>

<p>To set the market for a security, pass a <code>market</code> argument to the <code>AddCrypto</code> method.</p>

<div class="section-example-container">
    <pre class="csharp">_symbol = AddCrypto("BTCUSD", market: Market.GDAX).Symbol;</pre>
    <pre class="python">self.symbol = self.AddCrypto("BTCUSD", market=Market.GDAX).Symbol</pre>
</div>

<?php echo file_get_contents(DOCS_RESOURCES."/securities/supported-markets.html"); ?>
