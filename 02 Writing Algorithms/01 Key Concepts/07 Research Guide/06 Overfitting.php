<?php echo file_get_contents(DOCS_RESOURCES."/research-guide/overfitting.php"); ?>

<p>If you have a collection of factors, you can backtest over a period of time to find the best performing factors for the time period. If you then narrow the collection of factors to just the best performing ones and backtest over the same period, the backtest will show great results. However, if you take the same best-performing factors and backtest them on an out-of-sample dataset, the performance will almost always underperform the in-sample period. To avoid issues with overfitting, follow these guidelines:</p>

<ul>
    <li>Use walk-forward optimization to train your models on historical data and test them on future data.</li>
    <li>Test your strategy with live paper trading.</li>
    <li>Test your model on different asset classes and markets.</li>
</ul>
