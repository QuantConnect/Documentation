<?
$symbolC = "var index = AddIndex(\"SPX\");
        var symbol = OptionChain(index.Symbol).OrderBy(c => c.OpenInterest).Last().Symbol;";
$symbolPy = "index = self.add_index('SPX')
        symbol = sorted(self.option_chain(index.symbol), key=lambda c: c.open_interest)[-1].symbol";
$assetClass = "IndexOption";
$dataTypeLink = "/docs/v2/writing-algorithms/securities/asset-classes/index-options/handling-data#05-Option-Contracts";
$dataType = "OpenInterest";
$dataFrame = "<div class='dataframe-wrapper'>
<table class='dataframe python'>
  <thead>
    <tr style='text-align: right;'>
      <th></th>
      <th></th>
      <th></th>
      <th></th>
      <th></th>
      <th>openinterest</th>
    </tr>
    <tr>
      <th>expiry</th>
      <th>strike</th>
      <th>type</th>
      <th>symbol</th>
      <th>time</th>
      <th></th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <th rowspan='5' valign='top'>2024-12-20</th>
      <th rowspan='5' valign='top'>4000.0</th>
      <th rowspan='5' valign='top'>1</th>
      <th rowspan='5' valign='top'>SPX 32NKZCP89T8EM|SPX 31</th>
      <th>2024-12-12 23:00:00</th>
      <td>306249.0</td>
    </tr>
    <tr>
      <th>2024-12-15 23:00:00</th>
      <td>305821.0</td>
    </tr>
    <tr>
      <th>2024-12-16 23:00:00</th>
      <td>301048.0</td>
    </tr>
    <tr>
      <th>2024-12-17 23:00:00</th>
      <td>299501.0</td>
    </tr>
    <tr>
      <th>2024-12-18 23:00:00</th>
      <td>294504.0</td>
    </tr>
  </tbody>
</table>
</div>";

$series = "expiry      strike  type  symbol                    time               
2024-12-20  4000.0  1     SPX 32NKZCP89T8EM|SPX 31  2024-12-15 23:00:00    -428.0
                                                    2024-12-16 23:00:00   -4773.0
                                                    2024-12-17 23:00:00   -1547.0
                                                    2024-12-18 23:00:00   -4997.0
Name: openinterest, dtype: float64";

include(DOCS_RESOURCES."/history/open_interest.php");
?>
