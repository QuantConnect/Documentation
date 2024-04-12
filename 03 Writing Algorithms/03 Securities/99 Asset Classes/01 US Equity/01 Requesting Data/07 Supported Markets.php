<p>LEAN groups all of the US Equity exchanges under <code>Market.USA</code>. In live mode, the brokerage routes the orders to the exchange that provides the best price.</p>

<p>To set the market for a security, pass a <code>market</code> argument to the <code>AddEquity</code> method.</p>

<div class="section-example-container">
    <pre class="csharp">_symbol = AddEquity("SPY", market: Market.USA).Symbol;</pre>
    <pre class="python">self.symbol = self.add_equity("SPY", market=Market.USA).symbol</pre>
</div>

<?php echo file_get_contents(DOCS_RESOURCES."/securities/supported-markets.html"); ?>
