<?
$symbolC = "AddCrypto(\"BTCUSD\", market: Market.Bitfinex).Symbol";
$symbolPy = "self.add_crypto('BTCUSD', market=Market.BITFINEX).symbol";
$assetClass = "Crypto";
$dataTypeLink = "/docs/v2/writing-algorithms/securities/asset-classes/crypto/handling-data#05-Ticks";
$dataType = "Tick";
$supportsTradeData = true;
$dataFrame = "<div class='dataframe-wrapper'>
<table class='dataframe python'>
  <thead>
    <tr style='text-align: right;'>
      <th></th>
      <th></th>
      <th>askprice</th>
      <th>asksize</th>
      <th>bidprice</th>
      <th>bidsize</th>
      <th>lastprice</th>
      <th>quantity</th>
    </tr>
    <tr>
      <th>symbol</th>
      <th>time</th>
      <th></th>
      <th></th>
      <th></th>
      <th></th>
      <th></th>
      <th></th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <th rowspan='5' valign='top'>BTCUSD</th>
      <th>2024-12-17 05:00:00.915569</th>
      <td>106390.0</td>
      <td>1.072804</td>
      <td>106380.0</td>
      <td>16.521214</td>
      <td>106385.0</td>
      <td>0.0</td>
    </tr>
    <tr>
      <th>2024-12-17 05:00:01.119192</th>
      <td>106390.0</td>
      <td>1.302212</td>
      <td>106380.0</td>
      <td>16.521214</td>
      <td>106385.0</td>
      <td>0.0</td>
    </tr>
    <tr>
      <th>2024-12-17 05:00:05.981284</th>
      <td>106390.0</td>
      <td>1.131937</td>
      <td>106380.0</td>
      <td>16.521214</td>
      <td>106385.0</td>
      <td>0.0</td>
    </tr>
    <tr>
      <th>2024-12-17 05:00:07.302974</th>
      <td>106390.0</td>
      <td>1.498471</td>
      <td>106380.0</td>
      <td>16.521214</td>
      <td>106385.0</td>
      <td>0.0</td>
    </tr>
    <tr>
      <th>2024-12-17 05:00:07.514624</th>
      <td>106390.0</td>
      <td>1.631937</td>
      <td>106380.0</td>
      <td>16.521214</td>
      <td>106385.0</td>
      <td>0.0</td>
    </tr>
  </tbody>
</table>
</div>
";

$filteredDataFrame = "<div class='dataframe-wrapper'>
<table class='dataframe python'>
  <thead>
    <tr style='text-align: right;'>
      <th></th>
      <th></th>
      <th>lastprice</th>
      <th>quantity</th>
    </tr>
    <tr>
      <th>symbol</th>
      <th>time</th>
      <th></th>
      <th></th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <th rowspan='5' valign='top'>BTCUSD</th>
      <th>2024-12-17 05:00:13.647</th>
      <td>106380.0</td>
      <td>0.201870</td>
    </tr>
    <tr>
      <th>2024-12-17 05:01:13.130</th>
      <td>106390.0</td>
      <td>0.000747</td>
    </tr>
    <tr>
      <th>2024-12-17 05:01:23.446</th>
      <td>106380.0</td>
      <td>0.197494</td>
    </tr>
    <tr>
      <th>2024-12-17 05:01:31.421</th>
      <td>106390.0</td>
      <td>0.031909</td>
    </tr>
    <tr>
      <th>2024-12-17 05:01:31.460</th>
      <td>106390.0</td>
      <td>0.000086</td>
    </tr>
  </tbody>
</table>
</div>";

include(DOCS_RESOURCES."/history/ticks.php");
?>
