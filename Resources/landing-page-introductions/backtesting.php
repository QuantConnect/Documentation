<p>Backtesting is the process of simulating a trading algorithm on historical data. By running a backtest, you can measure how the algorithm would have performed in the past. Although past performance doesn't guarantee future results, an algorithm that has a proven track record can provide investors with more confidence when deploying to live trading than an algorithm that hasn't performed favorably in the past. 
<?php if ($cloudPlatform) { ?>
Use the QuantConnect platform to run your backtests because we have institutional-grade datasets, an open-source backtesting engine that's constantly being improved, cloud servers to execute the backtests, and the backtesting hardware is maintained 24/7 by QuantConnect engineers.
<?php } 
if ($localPlatform) { ?>
If you run local backtests, you can leverage your local data and hardware. If you run backtests in the QuantConnect Cloud, you have access to our institutional-grade datasets and backtesting hardware that's maintained 24/7 by our team of engineers.
<?php } ?></p>