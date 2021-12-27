<p>Runtime statistics of your live algorithm are displayed in the banner at the top of the live performance page. You can create your own custom runtime statistics, but the following statistics are included by default:</p>

<ul>
    <li><b>PSR</b>: Probabilistic Sharpe Ratio</li>
    <li><b>Unrealized</b>: Unrealized profit</li>
    <li><b>Fees</b>: Total fees paid during the algorithm operation across all securities in the portfolio</li>
    <li><b>Net Profit</b>: Sum of all gross profit across all securities in the portfolio</li>
    <li><b>Return</b>: Return = (current equity - starting equity) / starting equity</li>
    <li><b>Equity</b>: Total portfolio value if we sold all holdings at current market rates</li>
    <li><b>Holdings</b>: Absolute sum of the items in the portfolio</li>
    <li><b>Volume</b>: Total sale volume since the start of algorithm operations</li>
</ul>

<img style="max-width: 100%; margin-bottom: 20px" src="https://cdn.quantconnect.com/i/tu/runtime-statistics.png">
<p>Call the <code>SetRuntimeStatistic</code> method with a <code>name</code> and <code>value</code> to add a runtime statistic to the banner. The <code>value</code> argument can be a <code>string</code> or a number. If you pass a number, it is cast to a <code>string</code>.</p>

<div class="section-example-container">
    <pre class="csharp">SetRuntimeStatistic(name, value);</pre>
    <pre class="python">self.SetRuntimeStatistic(name, value)</pre>
</div>
