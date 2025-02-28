<?
$symbolC = "AddForex(\"EURUSD\").Symbol";
$symbolPy = "self.add_forex('EURUSD').symbol";
$assetClass = "Forex";
$dataTypeLink = "/docs/v2/writing-algorithms/securities/asset-classes/forex/handling-data#03-Ticks";
$dataType = "Tick";
$dataFrame = "<div class='dataframe-wrapper'>
<table class='dataframe python'>
  <thead>
    <tr style='text-align: right;'>
      <th></th>
      <th></th>
      <th>askprice</th>
      <th>bidprice</th>
      <th>lastprice</th>
    </tr>
    <tr>
      <th>symbol</th>
      <th>time</th>
      <th></th>
      <th></th>
      <th></th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <th rowspan='5' valign='top'>EURUSD</th>
      <th>2024-12-17 00:00:01.113566</th>
      <td>1.0509</td>
      <td>1.05074</td>
      <td>1.050820</td>
    </tr>
    <tr>
      <th>2024-12-17 00:00:01.158183</th>
      <td>1.0509</td>
      <td>1.05076</td>
      <td>1.050830</td>
    </tr>
    <tr>
      <th>2024-12-17 00:00:08.514324</th>
      <td>1.0509</td>
      <td>1.05074</td>
      <td>1.050820</td>
    </tr>
    <tr>
      <th>2024-12-17 00:00:09.034650</th>
      <td>1.0509</td>
      <td>1.05075</td>
      <td>1.050825</td>
    </tr>
    <tr>
      <th>2024-12-17 00:00:09.222588</th>
      <td>1.0509</td>
      <td>1.05074</td>
      <td>1.050820</td>
    </tr>
  </tbody>
</table>
</div>";

$series = "symbol  time                      
EURUSD  2024-12-17 00:00:01.113566    0.00016
        2024-12-17 00:00:01.158183    0.00014
        2024-12-17 00:00:08.514324    0.00016
        2024-12-17 00:00:09.034650    0.00015
        2024-12-17 00:00:09.222588    0.00016
dtype: float64";

include(DOCS_RESOURCES."/history/ticks-for-pairs.php");
?>
