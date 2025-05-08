<?
$symbolC = "var future = AddFuture(Futures.Indices.SP500EMini);
        var symbol = FuturesChain(future.Symbol).First().Symbol;";
$symbolPy = "future = self.add_future(Futures.Indices.SP_500_E_MINI)
        symbol = list(self.futures_chain(future.symbol))[0].symbol";
$assetClass = "Future";
$dataTypeLink = "/docs/v2/writing-algorithms/securities/asset-classes/futures/handling-data#06-Futures-Contracts";
$dataType = "OpenInterest";
$dataFrame = "<div class='dataframe-wrapper'>
<table class='dataframe python'>
  <thead>
    <tr style='text-align: right;'>
      <th></th>
      <th></th>
      <th></th>
      <th>openinterest</th>
    </tr>
    <tr>
      <th>expiry</th>
      <th>symbol</th>
      <th>time</th>
      <th></th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <th rowspan='5' valign='top'>2024-12-20 13:30:00</th>
      <th rowspan='5' valign='top'>ES YOGVNNAOI1OH</th>
      <th>2024-12-14 19:00:00</th>
      <td>1631020.0</td>
    </tr>
    <tr>
      <th>2024-12-15 19:00:00</th>
      <td>1625312.0</td>
    </tr>
    <tr>
      <th>2024-12-16 19:00:00</th>
      <td>965789.0</td>
    </tr>
    <tr>
      <th>2024-12-17 19:00:00</th>
      <td>687472.0</td>
    </tr>
    <tr>
      <th>2024-12-18 19:00:00</th>
      <td>556365.0</td>
    </tr>
  </tbody>
</table>
</div>";

$series = "expiry               symbol           time               
2024-12-20 13:30:00  ES YOGVNNAOI1OH  2024-12-15 19:00:00     -5708.0
                                      2024-12-16 19:00:00   -659523.0
                                      2024-12-17 19:00:00   -278317.0
                                      2024-12-18 19:00:00   -131107.0
Name: openinterest, dtype: float64";

include(DOCS_RESOURCES."/history/open_interest.php");
?>
