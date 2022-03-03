<p>The top of the backtest report displays  statistics to summarize your algorithm's performance. The following table describes the key statistics in the report:</p>

<?php
include(DOCS_RESOURCES."/glossary.php");
echo "
<table class='table qc-table'>
    <thead>
        <tr>
            <th>Statistic</th>
            <th>Description</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Turnover</td>
            <td>The percentage of the algorithm's portfolio that was replaced in a given year.</td>
        </tr>
        <tr>
            <td>CAGR</td>
            <td>{$definitionByTerm['compounding annual return']}</td>
        </tr>
        <tr>
            <td>Markets</td>
            <td>The asset classes that the algorithm trades.</td>
        </tr>
        <tr>
            <td>Trades per day</td>
            <td>The total number of trades during the backtest divided by the number of days in the backtest. Trades per day is an approximation of the algorithm's trading frequency.</td>
        </tr>
        <tr>
            <td>Drawdown</td>
            <td>{$definitionByTerm['drawdown']}</td>
        </tr>
        <tr>
            <td>PSR</td>
            <td>{$definitionByTerm['probabilistic sharpe ratio (PSR)']}</td>
        </tr>
        <tr>
            <td>Sharpe Ratio</td>
            <td>{$definitionByTerm['sharpe ratio']}</td>
        </tr>
        <tr>
            <td>Information Ratio</td>
            <td>{$definitionByTerm['information ratio']}</td>
        </tr>
        <tr>
            <td>Strategy Capacity</td>
            <td>{$definitionByTerm['capacity']}</td>
        </tr>
    </tbody>
</table>";

?>
