<p>The banner at the top of the backtest results page displays the performance statistics of your backtest.</p>

<img class='img-responsive' src="https://cdn.quantconnect.com/i/tu/runtime-statistics.png">

<p>The following table describes the runtime statistics:</p>

<?php
include(DOCS_RESOURCES."/glossary.php");
echo "
<table class='qc-table table'>
  <thead>
    <tr>
      <th style='width: 25%'>Statistic</th>
      <th style='width: 75%'>Description</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td>PSR----</td>
      <td>{$defintionByTerm['probabilistic sharpe ratio (PSR)']}</td>
    </tr>
    <tr>
      <td>Unrealized</td>
      <td>Unrealized profit</td>
    </tr>
    <tr>
      <td>Fees</td>
      <td>Total fees paid during the backtest across all securities in the portfolio</td>
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
      <td>Total sale volume since the start of backtest</td>
    </tr>
    <tr>
      <td><a href='/docs/v2/our-platform/user-guides/alpha-streams-market/investing#02-Capacity'>Capacity</a></td>
      <td>The maximum amount of money the algorithm can trade before its performance degrades from market impact</td>
    </tr>
  </tbody>
</table>";
?>

<?php echo file_get_contents(DOCS_RESOURCES."/create-custom-runtime-statistic.html"); ?>
