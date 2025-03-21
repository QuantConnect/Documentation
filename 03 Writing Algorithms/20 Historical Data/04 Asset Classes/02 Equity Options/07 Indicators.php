<?
$underlyingSymbolC = "AddEquity(\"SPY\").Symbol";
$underlyingSymbolPy = "self.add_equity('SPY').symbol";
$assetClass = "EquityOption";
$indicatorLink = "/docs/v2/writing-algorithms/securities/asset-classes/equity-options/greeks-and-implied-volatility/indicators";
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
      <th>2024-12-12 16:00:00</th>
      <td>0.377619</td>
      <td>7.930</td>
    </tr>
    <tr>
      <th>2024-12-13 16:00:00</th>
      <td>0.366190</td>
      <td>7.690</td>
    </tr>
    <tr>
      <th>2024-12-16 16:00:00</th>
      <td>0.353810</td>
      <td>7.430</td>
    </tr>
    <tr>
      <th>2024-12-17 16:00:00</th>
      <td>0.336667</td>
      <td>7.070</td>
    </tr>
    <tr>
      <th>2024-12-18 16:00:00</th>
      <td>0.356429</td>
      <td>7.485</td>
    </tr>
  </tbody>
</table>
</div>";

include(DOCS_RESOURCES."/history/option_indicators.php");
?>
