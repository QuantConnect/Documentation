<p>
    We fill market orders immediately and completely in backtests<?= $paperTradingSupported ? ". In " .$brokerageName. " paper trading and" : " and <a href='/docs/v2/cloud-platform/live-trading/brokerages/quantconnect-paper-trading'>QuantConnect Paper Trading</a>. In "?> live trading, if the quantity of your market orders exceeds the quantity available at the top of the order book, your orders are filled according to what is available in the order book.
</p>
