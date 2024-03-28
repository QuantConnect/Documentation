<?php echo file_get_contents(DOCS_RESOURCES."/enumerations/market-cfd.html"); ?>

<div class="section-example-container">
    <pre class="csharp">_symbol = AddCfd("XAUUSD", market: Market.Oanda).Symbol;
_ibkrSymbol = AddCfd("IBGB100", market: Market.InteractiveBrokers).Symbol;   // Unavailable in backtesting
    </pre>
    <pre class="python">self.symbol = self.AddCfd("XAUUSD", market=Market.Oanda).Symbol
self.ibkr_symbol = self.AddCfd("IBGB100", market=Market.InteractiveBrokers).Symbol   # Unavailable in backtesting
    </pre>
</div>

<?php echo file_get_contents(DOCS_RESOURCES."/securities/supported-markets.html"); ?>
