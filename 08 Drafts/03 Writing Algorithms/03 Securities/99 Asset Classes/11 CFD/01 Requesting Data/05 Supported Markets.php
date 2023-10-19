<p>The only market available for CFD contracts is <code>Market.Oanda</code>, so you don't need to pass a <code>market</code> argument to the <code>AddCfd</code> method.</p>

<div class="section-example-container">
    <pre class="csharp">_symbol = AddCfd("XAUUSD", market: Market.Oanda).Symbol;</pre>
    <pre class="python">self.symbol = self.AddCfd("XAUUSD", market=Market.Oanda).Symbol</pre>
</div>

<?php echo file_get_contents(DOCS_RESOURCES."/securities/supported-markets.html"); ?>
