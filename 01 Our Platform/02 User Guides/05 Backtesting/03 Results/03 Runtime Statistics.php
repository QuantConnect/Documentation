<p>The banner at the top of the backtest results page displays the performance statistics of your backtest.</p>

<img class='docs-image' src="https://cdn.quantconnect.com/i/tu/runtime-statistics.png">

<p>The banner displays the following statistics:<br></p>

<ul>
    <li><span class='ul-option'>PSR</span>: <a href='/docs/v2/our-platform/user-guides/optimization/objectives#05-PSR'>Probabilistic Sharpe Ratio</a><br></li>
    <li><span class='ul-option'>Unrealized</span>: Unrealized profit<br></li>
    <li><span class='ul-option'>Fees</span>: Total fees paid during the backtest across all securities in the portfolio</li>
    <li><span class='ul-option'>Net Profit</span>: Sum of all gross profit across all securities in the portfolio</li>
    <li><span class='ul-option'>Return</span>: Return = (current equity - starting equity) / starting equity<br></li>
    <li><span class='ul-option'>Equity</span>: Total portfolio value if you sold all holdings at current market rates</li>
    <li><span class='ul-option'>Holdings</span>: Absolute sum of the items in the portfolio</li>
    <li><span class='ul-option'>Volume</span>: Total sale volume since the start of backtest</li>
    <li><span class='ul-option'>Capacity</span>: The maximum amount of capital the strategy can trade.<br></li>
</ul>

<?php echo file_get_contents(DOCS_RESOURCES."/create-custom-runtime-statistic.html"); ?>
