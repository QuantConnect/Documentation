<?
$symbolC = "var equity = AddEquity(\"SPY\", dataNormalizationMode: DataNormalizationMode.Raw);
        var symbol = OptionChain(equity.Symbol).OrderBy(c => c.OpenInterest).Last().Symbol;";
$symbolPy = "equity = self.add_equity('SPY', data_normalization_mode=DataNormalizationMode.RAW)
        symbol = sorted(self.option_chain(equity.symbol), key=lambda c: c.open_interest)[-1].symbol";
$assetClass = "EquityOption";
$dataTypeLink = "/docs/v2/writing-algorithms/securities/asset-classes/equity-options/handling-data#02-Trades";
$dataType = "TradeBar";
$dataFrame = "<div class='dataframe-wrapper'>
<table class='dataframe python'>
  <thead>
    <tr style='text-align: right;'>
      <th></th>
      <th></th>
      <th></th>
      <th></th>
      <th></th>
      <th>close</th>
      <th>high</th>
      <th>low</th>
      <th>open</th>
      <th>volume</th>
    </tr>
    <tr>
      <th>expiry</th>
      <th>strike</th>
      <th>type</th>
      <th>symbol</th>
      <th>time</th>
      <th></th>
      <th></th>
      <th></th>
      <th></th>
      <th></th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <th rowspan='5' valign='top'>2025-01-17</th>
      <th rowspan='5' valign='top'>440.0</th>
      <th rowspan='5' valign='top'>1</th>
      <th rowspan='5' valign='top'>SPY 32OCGBPPW6DTY|SPY R735QTJ8XC9X</th>
      <th>2024-12-12 16:00:00</th>
      <td>0.20</td>
      <td>0.23</td>
      <td>0.20</td>
      <td>0.21</td>
      <td>99.0</td>
    </tr>
    <tr>
      <th>2024-12-13 16:00:00</th>
      <td>0.20</td>
      <td>0.21</td>
      <td>0.17</td>
      <td>0.18</td>
      <td>543.0</td>
    </tr>
    <tr>
      <th>2024-12-16 16:00:00</th>
      <td>0.18</td>
      <td>0.19</td>
      <td>0.17</td>
      <td>0.17</td>
      <td>58.0</td>
    </tr>
    <tr>
      <th>2024-12-17 16:00:00</th>
      <td>0.24</td>
      <td>0.24</td>
      <td>0.19</td>
      <td>0.19</td>
      <td>101.0</td>
    </tr>
    <tr>
      <th>2024-12-18 16:00:00</th>
      <td>0.85</td>
      <td>0.85</td>
      <td>0.21</td>
      <td>0.21</td>
      <td>160.0</td>
    </tr>
  </tbody>
</table>
</div>";

$series = "expiry      strike  type  symbol                              time               
2025-01-17  440.0   1     SPY 32OCGBPPW6DTY|SPY R735QTJ8XC9X  2024-12-13 16:00:00    0.000000
                                                              2024-12-16 16:00:00   -0.100000
                                                              2024-12-17 16:00:00    0.333333
                                                              2024-12-18 16:00:00    2.541667
Name: close, dtype: float64";

include(DOCS_RESOURCES."/history/tradebars.php");
?>

<p>Request minute, hour, or daily resolution data. Otherwise, the history request won't return any data.</p>
