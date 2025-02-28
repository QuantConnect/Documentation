<?
$symbolC = "AddEquity(\"SPY\").Symbol";
$targetSymbolC = "AddEquity(\"AAPL\").Symbol";
$symbolPy = "self.add_equity('SPY').symbol";
$targetSymbolPy = "self.add_equity('AAPL').symbol";
$assetClass = "USEquity";
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
        <th>2024-12-12 16:00:00</th>
        <td>599.186667</td>
        <td>12582.92</td>
      </tr>
      <tr>
        <th>2024-12-13 16:00:00</th>
        <td>599.520952</td>
        <td>12589.94</td>
      </tr>
      <tr>
        <th>2024-12-16 16:00:00</th>
        <td>600.160952</td>
        <td>12603.38</td>
      </tr>
      <tr>
        <th>2024-12-17 16:00:00</th>
        <td>601.043810</td>
        <td>12621.92</td>
      </tr>
      <tr>
        <th>2024-12-18 16:00:00</th>
        <td>600.954762</td>
        <td>12620.05</td>
      </tr>
    </tbody>
  </table>
</div>";

include(DOCS_RESOURCES."/history/indicators.php");
?>
