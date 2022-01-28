<p>The banner at the top of the live performance page displays the performance statistics of your algorithm.</p>

<img class='img-responsive' src="https://cdn.quantconnect.com/i/tu/runtime-statistics-live.png">

<p>The following table shows the performance statistics that display by default:</p>


<table class="table qc-table">
    <thead>
        <tr>
            <th style="width: 25%">Statistic</th>
            <th style="width: 75%">Description</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><a href='/docs/v2/our-platform/user-guides/optimization/objectives#05-PSR'>PSR</a></td>
            <td>Probabilistic Sharpe Ratio</td>
        </tr>
        <tr>
            <td>Unrealized</td>
            <td>Unrealized profit</td>
        </tr>
        <tr>
            <td>Fees</td>
            <td>Total fees paid during the live deployment across all securities in the portfolio</td>
        </tr>
        <tr>
            <td>Net Profit</td>
            <td>Sum of all gross profit across all securities in the portfolio</td>
        </tr>
        <tr>
            <td>Return</td>
            <td>Return = (current equity - starting equity) / starting equity</td>
        </tr>
        <tr>
            <td>Equity</td>
            <td>Total portfolio value if you sold all holdings at current market rates</td>
        </tr>
        <tr>
            <td>Holdings</td>
            <td>Absolute sum of the items in the portfolio</td>
        </tr>
        <tr>
            <td>Volume</td>
            <td>Total sale volume since the start of the live deployment</td>
        </tr>
    </tbody>
</table>


<?php echo file_get_contents(DOCS_RESOURCES."/create-custom-runtime-statistic.html"); ?>
