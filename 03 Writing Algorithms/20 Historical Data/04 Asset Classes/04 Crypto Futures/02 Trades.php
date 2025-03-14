<?
$symbolC = "var symbol = AddCryptoFuture(\"BTCUSD\").Symbol;";
$symbolPy = "symbol = self.add_crypto_future('BTCUSD').symbol";
$assetClass = "CryptoFuture";
$dataTypeLink = "/docs/v2/writing-algorithms/securities/asset-classes/crypto-futures/handling-data#03-Trades";
$dataType = "TradeBar";
$dataFrame = "<div class='dataframe-wrapper'>
<table class='dataframe python'>
  <thead>
    <tr style='text-align: right;'>
      <th></th>
      <th></th>
      <th>close</th>
      <th>high</th>
      <th>low</th>
      <th>open</th>
      <th>volume</th>
    </tr>
    <tr>
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
      <th rowspan='5' valign='top'>BTCUSD</th>
      <th>2024-12-15</th>
      <td>101383.8</td>
      <td>102683.9</td>
      <td>100563.7</td>
      <td>101403.8</td>
      <td>16666498.0</td>
    </tr>
    <tr>
      <th>2024-12-16</th>
      <td>104500.0</td>
      <td>105323.5</td>
      <td>101209.8</td>
      <td>101383.9</td>
      <td>26232316.0</td>
    </tr>
    <tr>
      <th>2024-12-17</th>
      <td>106120.0</td>
      <td>107872.1</td>
      <td>103320.0</td>
      <td>104500.0</td>
      <td>44897036.0</td>
    </tr>
    <tr>
      <th>2024-12-18</th>
      <td>106171.2</td>
      <td>108496.9</td>
      <td>105369.3</td>
      <td>106120.0</td>
      <td>41849071.0</td>
    </tr>
    <tr>
      <th>2024-12-19</th>
      <td>100163.2</td>
      <td>106550.2</td>
      <td>99911.0</td>
      <td>106171.2</td>
      <td>65325574.0</td>
    </tr>
  </tbody>
</table>
</div>";

$series = "symbol  time      
BTCUSD  2024-12-16    0.030737
        2024-12-17    0.015502
        2024-12-18    0.000482
        2024-12-19   -0.056588
Name: close, dtype: float64";

include(DOCS_RESOURCES."/history/tradebars.php");
?>
