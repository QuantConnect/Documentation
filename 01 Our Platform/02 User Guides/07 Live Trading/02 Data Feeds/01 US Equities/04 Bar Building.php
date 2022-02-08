<p>Ticks are aggregated to build bars. If a tick is flagged as suspicious, it's not included it in the bar price, but it's included in the bar volume. In backtesting, ticks are collected into slices that span 1 millisecond before they are injected into your algorithm. In live trading, ticks are collected into slices that span up to 70 milliseconds before they are injected into your algorithm.</p>

<h4>Discrepancies</h4>
<?php echo file_get_contents(DOCS_RESOURCES."/datasets/live-and-backtest-differences.html"); ?>

<h4>Opening and Closing Auctions</h4>
<?php echo file_get_contents(DOCS_RESOURCES."/datasets/opening-and-closing-auctions.html"); ?>
