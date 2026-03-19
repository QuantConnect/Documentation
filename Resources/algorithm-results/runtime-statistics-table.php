<p>The following table describes the default runtime statistics:</p>
<? include(DOCS_RESOURCES."/glossary.php"); ?>

<table class='qc-table table'>
  <thead>
    <tr>
      <th style='width: 25%'>Statistic</th>
      <th style='width: 75%'>Description</th>
    </tr>
  </thead>
  <tbody>
  <tr>
    <td>Equity</td>
    <td><?=$definitionByTerm['equity']?></td>
  </tr>
  <tr>
    <td>Fees</td>
    <td><?=$definitionByTerm['total fees']?></td>
  </tr>
  <tr>
    <td>Holdings</td>
    <td><?=$definitionByTerm['holdings']?></td>
  </tr>
  <tr>
    <td>Net Profit</td>
    <td><?=$definitionByTerm['net profit']['Dollar-value']?></td>
  </tr>
  <tr>
    <td>PSR</td>
    <td><?=$definitionByTerm['Probabilistic Sharpe ratio']?></td>
  </tr>
  <tr>
    <td>Return</td>
    <td><?=$definitionByTerm['net profit']['Percent']?></td>
  </tr>
  <tr>
    <td>Unrealized</td>
    <td><?=$definitionByTerm['unrealized']?></td>
  </tr>
  <tr>
    <td>Volume</td>
    <td><?=$definitionByTerm['volume']?></td>
  </tr>    
</tbody>
</table>
