<p>The banner at the top of the live performance page displays the performance statistics of your algorithm.</p>

<img class='img-responsive' src="https://cdn.quantconnect.com/i/tu/runtime-statistics-live.png">

<p>The following table shows the performance statistics that display by default:</p>

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
      <td>PSR</td>
      <td>{$defintionByTerm['probabilistic sharpe ratio (PSR)']}</td>
    </tr>
    <tr>
      <td>Unrealized</td>
      <td>{$defintionByTerm['unrealized']}</td>
    </tr>
    <tr>
      <td>Fees</td>
      <td>{$defintionByTerm['total fees']}</td>
    </tr>
    <tr>
      <td>Net Profit</td>
      <td>{$defintionByTerm['net profit']['dollar-value']}</td>
    </tr>
    <tr>
      <td>Return</td>
      <td>{$defintionByTerm['net profit']['percent']}</td>
    </tr>
    <tr>
      <td>Equity</td>
      <td>Total portfolio value if you sold all holdings at current market rates</td>
    </tr>
    <tr>
      <td>Holdings</td>
      <td>{$defintionByTerm['holdings']}</td>
    </tr>
    <tr>
      <td>Volume</td>
      <td>{$defintionByTerm['volume']}</td>
    </tr>
  </tbody>
</table>";


echo file_get_contents(DOCS_RESOURCES."/create-custom-runtime-statistic.html"); 
?>
