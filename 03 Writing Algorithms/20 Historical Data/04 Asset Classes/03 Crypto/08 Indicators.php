<?
$symbolC = "AddCrypto(\"BTCUSD\").Symbol";
$targetSymbolC = "AddCrypto(\"DOGEUSD\").Symbol";
$symbolPy = "self.add_crypto('BTCUSD').symbol";
$targetSymbolPy = "self.add_crypto('DOGEUSD').symbol";
$assetClass = "Crypto";
$supportsTradeData = true;
$dataFrame = "<div class='dataframe-wrapper'>
<table class='dataframe python'>
  <thead>
    <tr style='text-align: right;'>
      <th></th>
      <th>current</th>
      <th>rollingsum</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <th>2024-12-15</th>
      <td>97734.062857</td>
      <td>2052415.32</td>
    </tr>
    <tr>
      <th>2024-12-16</th>
      <td>98039.381905</td>
      <td>2058827.02</td>
    </tr>
    <tr>
      <th>2024-12-17</th>
      <td>98663.181905</td>
      <td>2071926.82</td>
    </tr>
    <tr>
      <th>2024-12-18</th>
      <td>99340.640952</td>
      <td>2086153.46</td>
    </tr>
    <tr>
      <th>2024-12-19</th>
      <td>99540.619048</td>
      <td>2090353.00</td>
    </tr>
  </tbody>
</table>

</div>";

include(DOCS_RESOURCES."/history/indicators.php");
?>
