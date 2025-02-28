
<?
$symbolC = "AddIndex(\"SPX\").Symbol";
$targetSymbolC = "AddIndex(\"NDX\").Symbol";
$symbolPy = "self.add_index('SPX').symbol";
$targetSymbolPy = "self.add_index('NDX').symbol";
$assetClass = "Index";
$supportsTradeData = false;
$dataFrame = "<div class='dataframe-wrapper'>
<table class=dataframe python>
  <thead>
    <tr style=text-align: right;>
      <th></th>
      <th>current</th>
      <th>rollingsum</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <th>2024-12-12 15:15:00</th>
      <td>6003.055714</td>
      <td>126064.17</td>
    </tr>
    <tr>
      <th>2024-12-13 15:15:00</th>
      <td>6006.096667</td>
      <td>126128.03</td>
    </tr>
    <tr>
      <th>2024-12-16 15:15:00</th>
      <td>6012.036190</td>
      <td>126252.76</td>
    </tr>
    <tr>
      <th>2024-12-17 15:15:00</th>
      <td>6020.634286</td>
      <td>126433.32</td>
    </tr>
    <tr>
      <th>2024-12-18 15:15:00</th>
      <td>6019.442381</td>
      <td>126408.29</td>
    </tr>
  </tbody>
</table>
</div>";

include(DOCS_RESOURCES."/history/indicators.php");
?>
