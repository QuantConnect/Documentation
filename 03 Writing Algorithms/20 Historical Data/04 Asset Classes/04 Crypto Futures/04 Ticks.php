<?
$symbolC = "AddCryptoFuture(\"BTCUSD\").Symbol";
$symbolPy = "self.add_crypto_future('BTCUSD').symbol";
$assetClass = "CryptoFuture";
$dataTypeLink = "/docs/v2/writing-algorithms/securities/asset-classes/crypto-futures/handling-data#05-Ticks";
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
      <th>2024-12-17 05:00:00.132868</th>
      <td>106537.1</td>
      <td>12342.0</td>
      <td>106537.0</td>
      <td>35.0</td>
      <td>106537.05</td>
      <td>0.0</td>
    </tr>
    <tr>
      <th>2024-12-17 05:00:00.145098</th>
      <td>106537.1</td>
      <td>12353.0</td>
      <td>106537.0</td>
      <td>35.0</td>
      <td>106537.05</td>
      <td>0.0</td>
    </tr>
    <tr>
      <th>2024-12-17 05:00:00.424485</th>
      <td>106537.1</td>
      <td>12353.0</td>
      <td>106537.0</td>
      <td>355.0</td>
      <td>106537.05</td>
      <td>0.0</td>
    </tr>
    <tr>
      <th>2024-12-17 05:00:00.427716</th>
      <td>106537.1</td>
      <td>12353.0</td>
      <td>106537.0</td>
      <td>354.0</td>
      <td>106537.05</td>
      <td>0.0</td>
    </tr>
    <tr>
      <th>2024-12-17 05:00:00.431177</th>
      <td>106537.1</td>
      <td>12353.0</td>
      <td>106537.0</td>
      <td>355.0</td>
      <td>106537.05</td>
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
      <th>2024-12-17 05:00:01.008</th>
      <td>106537.1</td>
      <td>2.0</td>
    </tr>
    <tr>
      <th>2024-12-17 05:00:02.729</th>
      <td>106537.0</td>
      <td>2.0</td>
    </tr>
    <tr>
      <th>2024-12-17 05:00:02.971</th>
      <td>106537.0</td>
      <td>5.0</td>
    </tr>
    <tr>
      <th>2024-12-17 05:00:05.977</th>
      <td>106537.0</td>
      <td>4.0</td>
    </tr>
    <tr>
      <th>2024-12-17 05:00:14.072</th>
      <td>106537.1</td>
      <td>56.0</td>
    </tr>
  </tbody>
</table>
</div>";

include(DOCS_RESOURCES."/history/ticks.php");
?>
