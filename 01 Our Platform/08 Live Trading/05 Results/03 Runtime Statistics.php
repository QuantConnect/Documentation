<p>The banner at the top of the live results page displays the performance statistics of your algorithm.</p>

<img class='docs-image' src="https://cdn.quantconnect.com/i/tu/live-runtime-statistics-1.png">

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
      <td>{$definitionByTerm['probabilistic sharpe ratio (PSR)']}</td>
    </tr>
    <tr>
      <td>Unrealized</td>
      <td>{$definitionByTerm['unrealized']}</td>
    </tr>
    <tr>
      <td>Fees</td>
      <td>{$definitionByTerm['total fees']}</td>
    </tr>
    <tr>
      <td>Net Profit</td>
      <td>{$definitionByTerm['net profit']['dollar-value']}</td>
    </tr>
    <tr>
      <td>Return</td>
      <td>{$definitionByTerm['net profit']['percent']}</td>
    </tr>
    <tr>
      <td>Equity</td>
      <td>{$definitionByTerm['equity']}</td>
    </tr>
    <tr>
      <td>Holdings</td>
      <td>{$definitionByTerm['holdings']}</td>
    </tr>
    <tr>
      <td>Volume</td>
      <td>{$definitionByTerm['volume']}</td>
    </tr>
  </tbody>
</table>";


echo file_get_contents(DOCS_RESOURCES."/algorithm-results/create-custom-runtime-statistic.html"); 
?>
