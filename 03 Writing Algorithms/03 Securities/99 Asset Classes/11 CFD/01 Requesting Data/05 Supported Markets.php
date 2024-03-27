<?php echo file_get_contents(DOCS_RESOURCES."/enumerations/market-cfd.html"); ?>

<div class="section-example-container">
    <pre class="csharp">_symbol = AddCfd("XAUUSD", market: Market.Oanda).Symbol;</pre>
    <pre class="python">self.symbol = self.AddCfd("XAUUSD", market=Market.Oanda).Symbol</pre>
</div>

<div class="section-example-container">
    <pre class="csharp">_symbol = AddCfd("IBGB100", market: Market.InteractiveBrokers).Symbol;</pre>
    <pre class="python">self.symbol = self.AddCfd("IBGB100", market=Market.InteractiveBrokers).Symbol</pre>
</div>


<?php echo file_get_contents(DOCS_RESOURCES."/securities/supported-markets.html"); ?>
