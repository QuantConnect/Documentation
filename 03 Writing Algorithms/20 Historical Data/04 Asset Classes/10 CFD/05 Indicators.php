<?
$symbolC = "AddCfd(\"SPX500USD\").Symbol";
$targetSymbolC = "AddCfd(\"XAUUSD\").Symbol";
$symbolPy = "self.add_cfd('SPX500USD').symbol";
$targetSymbolPy = "self.add_cfd('XAUUSD').symbol";
$assetClass = "CFD";
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
      <th>2024-12-13 18:00:00</th>
      <td>6033.309524</td>
      <td>126699.5</td>
    </tr>
    <tr>
      <th>2024-12-15 18:00:00</th>
      <td>6039.390476</td>
      <td>126827.2</td>
    </tr>
    <tr>
      <th>2024-12-16 18:00:00</th>
      <td>6045.090476</td>
      <td>126946.9</td>
    </tr>
    <tr>
      <th>2024-12-17 18:00:00</th>
      <td>6049.180952</td>
      <td>127032.8</td>
    </tr>
    <tr>
      <th>2024-12-18 18:00:00</th>
      <td>6044.276190</td>
      <td>126929.8</td>
    </tr>
  </tbody>
</table>
</div>";

include(DOCS_RESOURCES."/history/indicators.php");
?>
