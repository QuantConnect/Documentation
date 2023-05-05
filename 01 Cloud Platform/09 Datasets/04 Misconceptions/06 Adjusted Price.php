<p>By default, LEAN adjusts US Equity data for splits and dividends to produce a smooth price curve.</p>

<? include(DOCS_RESOURCES."/datasets/data-normalization.php"); ?>

<p>Backtest differences occur when you run backtests before and after splits and dividends since the adjusted prices will be different. The difference can be significant in large universes because of multiple corporate actions and the cummulative effect of orders with a small difference.</p>