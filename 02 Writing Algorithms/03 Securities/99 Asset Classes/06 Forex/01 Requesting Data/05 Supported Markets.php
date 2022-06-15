<p>LEAN groups all of the Forex exchanges under <code>Market.Oanda</code>. In live mode, the brokerage routes the orders to the exchange that provides the best price.</p>

<p>To set the market for a Forex pair, pass a <code>market</code> argument to the <code>AddForex</code> method.</p>

<div class="section-example-container">
    <pre class="csharp">_symbol = AddForex("EURUSD", market: Market.Oanda).Symbol;</pre>
    <pre class="python">self.symbol = self.AddForex("EURUSD", market=Market.Oanda).Symbol</pre>
</div>

<?php echo file_get_contents(DOCS_RESOURCES."/securities/supported-markets.html"); ?>
