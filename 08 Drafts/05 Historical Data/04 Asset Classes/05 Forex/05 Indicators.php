<?
$symbolC = "AddForex(\"EURUSD\").Symbol";
$targetSymbolC = "AddForex(\"AUDUSD\").Symbol";
$symbolPy = "self.add_forex('EURUSD').symbol";
$targetSymbolPy = "self.add_forex('AUDUSD').symbol";
$assetClass = "Forex";
$supportsTradeData = false;
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
      <th>2024-12-13 19:00:00</th>
      <td>1.051928</td>
      <td>22.090485</td>
    </tr>
    <tr>
      <th>2024-12-15 19:00:00</th>
      <td>1.051755</td>
      <td>22.086865</td>
    </tr>
    <tr>
      <th>2024-12-16 19:00:00</th>
      <td>1.051983</td>
      <td>22.091650</td>
    </tr>
    <tr>
      <th>2024-12-17 19:00:00</th>
      <td>1.052336</td>
      <td>22.099055</td>
    </tr>
    <tr>
      <th>2024-12-18 19:00:00</th>
      <td>1.051723</td>
      <td>22.086175</td>
    </tr>
  </tbody>
</table>
</div>";

include(DOCS_RESOURCES."/history/indicators.php");
?>
