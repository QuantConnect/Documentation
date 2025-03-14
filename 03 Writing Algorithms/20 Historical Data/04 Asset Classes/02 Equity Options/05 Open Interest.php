<?
$symbolC = "var equity = AddEquity(\"SPY\", dataNormalizationMode: DataNormalizationMode.Raw);
        var symbol = OptionChain(equity.Symbol).OrderBy(c => c.OpenInterest).Last().Symbol;";
$symbolPy = "equity = self.add_equity('SPY', data_normalization_mode=DataNormalizationMode.RAW)
        symbol = sorted(self.option_chain(equity.symbol), key=lambda c: c.open_interest)[-1].symbol";
$assetClass = "EquityOption";
$dataTypeLink = "/docs/v2/writing-algorithms/securities/asset-classes/equity-options/handling-data#05-Option-Contracts";
$dataType = "OpenInterest";
$dataFrame = "<div class='dataframe-wrapper'>
<table class='dataframe python'>
  <thead>
    <tr style='text-align: right;'>
      <th></th>
      <th></th>
      <th></th>
      <th></th>
      <th></th>
      <th>openinterest</th>
    </tr>
    <tr>
      <th>expiry</th>
      <th>strike</th>
      <th>type</th>
      <th>symbol</th>
      <th>time</th>
      <th></th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <th rowspan='5' valign='top'>2025-01-17</th>
      <th rowspan='5' valign='top'>440.0</th>
      <th rowspan='5' valign='top'>1</th>
      <th rowspan='5' valign='top'>SPY   250117P00440000</th>
      <th>2024-12-13</th>
      <td>171751.0</td>
    </tr>
    <tr>
      <th>2024-12-16</th>
      <td>172190.0</td>
    </tr>
    <tr>
      <th>2024-12-17</th>
      <td>172157.0</td>
    </tr>
    <tr>
      <th>2024-12-18</th>
      <td>172147.0</td>
    </tr>
    <tr>
      <th>2024-12-19</th>
      <td>172099.0</td>
    </tr>
  </tbody>
</table>
</div>";

$series = "expiry      strike  type  symbol                 time      
2025-01-17  440.0   1     SPY   250117P00440000  2024-12-16    439.0
                                                 2024-12-17    -33.0
                                                 2024-12-18    -10.0
                                                 2024-12-19    -48.0
Name: openinterest, dtype: float64";

include(DOCS_RESOURCES."/history/open_interest.php");
?>
