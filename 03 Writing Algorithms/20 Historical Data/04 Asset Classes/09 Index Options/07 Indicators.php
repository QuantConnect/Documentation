<?
$underlyingSymbolC = "AddIndex(\"SPX\").Symbol";
$underlyingSymbolPy = "self.add_index('SPX').symbol";
$assetClass = "IndexOption";
$indicatorLink = "/docs/v2/writing-algorithms/securities/asset-classes/index-options/greeks-and-implied-volatility/indicators";
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
      <th>2024-12-12 15:15:00</th>
      <td>0.617857</td>
      <td>12.975</td>
    </tr>
    <tr>
      <th>2024-12-13 15:15:00</th>
      <td>0.580952</td>
      <td>12.200</td>
    </tr>
    <tr>
      <th>2024-12-16 15:15:00</th>
      <td>0.535714</td>
      <td>11.250</td>
    </tr>
    <tr>
      <th>2024-12-17 15:15:00</th>
      <td>0.477381</td>
      <td>10.025</td>
    </tr>
    <tr>
      <th>2024-12-18 15:15:00</th>
      <td>0.472619</td>
      <td>9.925</td>
    </tr>
  </tbody>
</table>
</div>";

include(DOCS_RESOURCES."/history/option_indicators.php");
?>
