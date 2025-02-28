<?
$symbolC = "AddCfd(\"XAUUSD\").Symbol";
$symbolPy = "self.add_cfd('XAUUSD').symbol";
$assetClass = "CFD";
$dataTypeLink = "/docs/v2/writing-algorithms/securities/asset-classes/cfd/handling-data#03-Ticks";
$dataType = "Tick";
$supportsTradeData = false;
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
      <th rowspan='5' valign='top'>XAUUSD</th>
      <th>2024-12-17 00:00:00.047969</th>
      <td>2652.07</td>
      <td>2651.61</td>
      <td>2651.840</td>
    </tr>
    <tr>
      <th>2024-12-17 00:00:00.716746</th>
      <td>2652.03</td>
      <td>2651.53</td>
      <td>2651.780</td>
    </tr>
    <tr>
      <th>2024-12-17 00:00:00.742011</th>
      <td>2652.02</td>
      <td>2651.49</td>
      <td>2651.755</td>
    </tr>
    <tr>
      <th>2024-12-17 00:00:00.770819</th>
      <td>2652.02</td>
      <td>2651.47</td>
      <td>2651.745</td>
    </tr>
    <tr>
      <th>2024-12-17 00:00:00.781622</th>
      <td>2652.02</td>
      <td>2651.49</td>
      <td>2651.755</td>
    </tr>
  </tbody>
</table>
</div>";

$series = "symbol  time                      
XAUUSD  2024-12-17 00:00:00.047969    0.46
        2024-12-17 00:00:00.716746    0.50
        2024-12-17 00:00:00.742011    0.53
        2024-12-17 00:00:00.770819    0.55
        2024-12-17 00:00:00.781622    0.53
dtype: float64";

include(DOCS_RESOURCES."/history/ticks.php");
?>
