<?php echo file_get_contents(DOCS_RESOURCES."/research-guide/overfitting.php"); ?>


- If you have a collection of factors, you can backtest over a period of time to find the best performing factors for the time period you selected. If you then narrow the collection of factors to just the best performing ones and backtest over the same period, the backtest will show great results. If you take the same best-performing factors and backtest them on an out-of-sample dataset, the performance will almost always underperform the in-sample period. If you take the same initial collection of factors but perform walk-forward optimization, you'll notice the performance as the overfit in-sample period with the best performing factors. 
<br>

- To avoid issues with overfitting:
<br>
&nbsp;&nbsp; - fit your models on one set of data and test them on another; use walkforward optimization.
<br>
&nbsp;&nbsp; - Test your strategy with live paper trading
<br>
&nbsp;&nbsp; - Fit your model on a universe of securities (say, equities), and then test your model on a different universe of equity securities.