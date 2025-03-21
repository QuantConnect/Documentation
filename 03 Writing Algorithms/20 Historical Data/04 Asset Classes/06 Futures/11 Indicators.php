<?
$symbolC = "AddFuture(Futures.Indices.SP500EMini).Symbol";
$targetSymbolC = "AddFuture(Futures.Metals.Gold).Symbol";
$symbolPy = "self.add_future(Futures.Indices.SP_500_E_MINI).symbol";
$targetSymbolPy = "self.add_future(Futures.Metals.GOLD).symbol";
$assetClass = "Futures";
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
      <th>2024-12-12 17:00:00</th>
      <td>6150.577121</td>
      <td>129162.119546</td>
    </tr>
    <tr>
      <th>2024-12-13 17:00:00</th>
      <td>6154.322490</td>
      <td>129240.772299</td>
    </tr>
    <tr>
      <th>2024-12-16 17:00:00</th>
      <td>6162.919815</td>
      <td>129421.316116</td>
    </tr>
    <tr>
      <th>2024-12-17 17:00:00</th>
      <td>6169.413410</td>
      <td>129557.681602</td>
    </tr>
    <tr>
      <th>2024-12-18 17:00:00</th>
      <td>6166.050570</td>
      <td>129487.061970</td>
    </tr>
  </tbody>
</table>

</div>";

include(DOCS_RESOURCES."/history/indicators.php");
?>
