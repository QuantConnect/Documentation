<p>The banner at the top of the backtest results page displays performance statistics of your backtest.</p>

<img style="max-width: 100%; margin-bottom: 20px" src="https://cdn.quantconnect.com/i/tu/runtime-statistics.png">

<p>You can create your own custom runtime statistics, but the following statistics are included by default:<br></p>

<ul>
    <li><b>PSR</b>: Probabilistic Sharpe Ratio<br></li>
    <li><b>Unrealized</b>: Unrealized profit<br></li>
    <li><b>Fees</b>: Total fees paid during the algorithm operation across all securities in the portfolio</li>
    <li><b>Net Profit</b>: Sum of all gross profit across all securities in the portfolio</li>
    <li><b>Return</b>: Return = (current equity - starting equity) / starting equity<br></li>
    <li><b>Equity</b>: Total portfolio value if we sold all holdings at current market rates</li>
    <li><b>Holdings</b>: Absolute sum of the items in the portfolio</li>
    <li><b>Volume</b>: Total sale volume since the start of algorithm operations</li>
    <li><b>Capacity</b>: The maximum amount of capital the strategy can trade.<br></li>
</ul>

<p>Call <code>SetRuntimeStatistic</code> with <code>name</code> and <code>value</code> arguments to add a runtime statistic to the banner. The <code>value</code> argument can be a <code>string</code> or a number. If you pass a number, it is cast to a <code>string</code>.</p>

<div class="section-example-container">
    <pre class="csharp">SetRuntimeStatistic("My Custom statistic", myCustomStatisticsValue);</pre>
    <pre class="python">self.SetRuntimeStatistic("My Custom statistic", my_custom_statistics_value)</pre>
</div>