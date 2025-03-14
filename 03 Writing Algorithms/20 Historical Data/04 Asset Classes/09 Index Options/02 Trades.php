<?
$symbolC = "var index = AddIndex(\"SPX\");
        var symbol = OptionChain(index.Symbol).OrderBy(c => c.OpenInterest).Last().Symbol;";
$symbolPy = "index = self.add_index('SPX')
        symbol = sorted(self.option_chain(index.symbol), key=lambda c: c.open_interest)[-1].symbol";
$assetClass = "IndexOption";
$dataTypeLink = "/docs/v2/writing-algorithms/securities/asset-classes/index-options/handling-data#02-Trades";
$dataType = "TradeBar";
$dataFrame = "<div class='dataframe-wrapper'>
<table class='dataframe python'>
  <thead>
    <tr style='text-align: right;'>
      <th></th>
      <th></th>
      <th></th>
      <th></th>
      <th></th>
      <th>close</th>
      <th>high</th>
      <th>low</th>
      <th>open</th>
      <th>volume</th>
    </tr>
    <tr>
      <th>expiry</th>
      <th>strike</th>
      <th>type</th>
      <th>symbol</th>
      <th>time</th>
      <th></th>
      <th></th>
      <th></th>
      <th></th>
      <th></th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <th rowspan='5' valign='top'>2024-12-20</th>
      <th rowspan='5' valign='top'>4000.0</th>
      <th rowspan='5' valign='top'>1</th>
      <th rowspan='5' valign='top'>SPX 32NKZCP89T8EM|SPX 31</th>
      <th>2024-12-12 15:15:00</th>
      <td>0.05</td>
      <td>0.07</td>
      <td>0.05</td>
      <td>0.07</td>
      <td>5773.0</td>
    </tr>
    <tr>
      <th>2024-12-13 15:15:00</th>
      <td>0.05</td>
      <td>0.10</td>
      <td>0.05</td>
      <td>0.05</td>
      <td>3052.0</td>
    </tr>
    <tr>
      <th>2024-12-16 15:15:00</th>
      <td>0.03</td>
      <td>0.10</td>
      <td>0.03</td>
      <td>0.10</td>
      <td>6423.0</td>
    </tr>
    <tr>
      <th>2024-12-17 15:15:00</th>
      <td>0.03</td>
      <td>0.05</td>
      <td>0.03</td>
      <td>0.05</td>
      <td>6085.0</td>
    </tr>
    <tr>
      <th>2024-12-18 15:15:00</th>
      <td>0.85</td>
      <td>1.00</td>
      <td>0.03</td>
      <td>0.03</td>
      <td>10551.0</td>
    </tr>
  </tbody>
</table>
</div>";

$series = "expiry      strike  type  symbol                    time               
2024-12-20  4000.0  1     SPX 32NKZCP89T8EM|SPX 31  2024-12-13 15:15:00     0.000000
                                                    2024-12-16 15:15:00    -0.400000
                                                    2024-12-17 15:15:00     0.000000
                                                    2024-12-18 15:15:00    27.333333";

include(DOCS_RESOURCES."/history/tradebars.php");
?>
