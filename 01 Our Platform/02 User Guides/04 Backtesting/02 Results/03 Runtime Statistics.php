<p>The banner at the top of the backtest results page displays the performance statistics of your backtest.</p>

<img style="max-width: 100%; margin-bottom: 20px" src="https://cdn.quantconnect.com/i/tu/runtime-statistics.png">

<p>You can create your own custom runtime statistics, but the following statistics are included by default:<br></p>

<ul>
    <li><b>PSR</b>: <a href='../optimization/objectives#05-PSR'>Probabilistic Sharpe Ratio</a><br></li>
    <li><b>Unrealized</b>: Unrealized profit<br></li>
    <li><b>Fees</b>: Total fees paid during the backtest across all securities in the portfolio</li>
    <li><b>Net Profit</b>: Sum of all gross profit across all securities in the portfolio</li>
    <li><b>Return</b>: Return = (current equity - starting equity) / starting equity<br></li>
    <li><b>Equity</b>: Total portfolio value if you sold all holdings at current market rates</li>
    <li><b>Holdings</b>: Absolute sum of the items in the portfolio</li>
    <li><b>Volume</b>: Total sale volume since the start of backtest</li>
    <li><b>Capacity</b>: The maximum amount of capital the strategy can trade.<br></li>
</ul>

<?php echo file_get_contents(DOCS_RESOURCES."/create-custom-runtime-statistic.html"); ?>
