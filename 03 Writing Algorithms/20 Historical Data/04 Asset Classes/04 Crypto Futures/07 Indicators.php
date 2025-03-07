<?
$symbolC = "AddCryptoFuture(\"BTCUSD\").Symbol";
$targetSymbolC = "AddCryptoFuture(\"DOGEUSD\").Symbol";
$symbolPy = "self.add_crypto_future('BTCUSD').symbol";
$targetSymbolPy = "self.add_crypto_future('DOGEUSD').symbol";
$assetClass = "CryptoFuture";
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
      <td>97759.090476</td>
      <td>2052940.9</td>
    </tr>
    <tr>
      <th>2024-12-16</th>
      <td>98065.342857</td>
      <td>2059372.2</td>
    </tr>
    <tr>
      <th>2024-12-17</th>
      <td>98689.038095</td>
      <td>2072469.8</td>
    </tr>
    <tr>
      <th>2024-12-18</th>
      <td>99363.738095</td>
      <td>2086638.5</td>
    </tr>
    <tr>
      <th>2024-12-19</th>
      <td>99563.576190</td>
      <td>2090835.1</td>
    </tr>
  </tbody>
</table>
</div>";

include(DOCS_RESOURCES."/history/indicators.php");
?>
