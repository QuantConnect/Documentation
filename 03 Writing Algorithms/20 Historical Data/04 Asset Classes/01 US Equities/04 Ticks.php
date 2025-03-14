<?
$symbolC = "var symbol = AddEquity(\"SPY\").Symbol;";
$symbolPy = "symbol = self.add_equity('SPY').symbol";
$assetClass = "USEquity";
$dataTypeLink = "/docs/v2/writing-algorithms/securities/asset-classes/us-equity/handling-data#05-Ticks";
$dataType = "Tick";
$supportsTradeData = true;
$dataFrame = "<div class='dataframe-wrapper'>
  <table class='dataframe python'>
    <thead>
      <tr style='text-align: right;'>
        <th></th>
        <th></th>
        <th>askprice</th>
        <th>asksize</th>
        <th>bidprice</th>
        <th>bidsize</th>
        <th>exchange</th>
        <th>lastprice</th>
        <th>quantity</th>
      </tr>
      <tr>
        <th>symbol</th>
        <th>time</th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <th rowspan='5' valign='top'>SPY</th>
        <th>2024-12-17 09:30:00.000214</th>
        <td>0.00</td>
        <td>0.0</td>
        <td>604.17</td>
        <td>1000.0</td>
        <td>NASDAQ</td>
        <td>604.17</td>
        <td>0.0</td>
      </tr>
      <tr>
        <th>2024-12-17 09:30:00.000214</th>
        <td>604.19</td>
        <td>1900.0</td>
        <td>0.00</td>
        <td>0.0</td>
        <td>ARCA</td>
        <td>604.19</td>
        <td>0.0</td>
      </tr>
      <tr>
        <th>2024-12-17 09:30:00.001110</th>
        <td>NaN</td>
        <td>NaN</td>
        <td>NaN</td>
        <td>NaN</td>
        <td>NYSE</td>
        <td>604.19</td>
        <td>100.0</td>
      </tr>
      <tr>
        <th>2024-12-17 09:30:00.001480</th>
        <td>NaN</td>
        <td>NaN</td>
        <td>NaN</td>
        <td>NaN</td>
        <td>ARCA</td>
        <td>604.19</td>
        <td>69.0</td>
      </tr>
      <tr>
        <th>2024-12-17 09:30:00.001482</th>
        <td>0.00</td>
        <td>0.0</td>
        <td>604.17</td>
        <td>1000.0</td>
        <td>NASDAQ</td>
        <td>604.17</td>
        <td>0.0</td>
      </tr>
    </tbody>
  </table>
</div>
";

$filteredDataFrame = "<div class='dataframe-wrapper'>
  <table class='dataframe python'>
    <thead>
      <tr style='text-align: right;'>
        <th></th>
        <th></th>
        <th>exchange</th>
        <th>lastprice</th>
        <th>quantity</th>
      </tr>
      <tr>
        <th>symbol</th>
        <th>time</th>
        <th></th>
        <th></th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <th rowspan='5' valign='top'>SPY</th>
        <th>2024-12-17 09:30:00.001110</th>
        <td>NYSE</td>
        <td>604.19</td>
        <td>100.0</td>
      </tr>
      <tr>
        <th>2024-12-17 09:30:00.001480</th>
        <td>ARCA</td>
        <td>604.19</td>
        <td>69.0</td>
      </tr>
      <tr>
        <th>2024-12-17 09:30:00.001483</th>
        <td>ARCA</td>
        <td>604.19</td>
        <td>100.0</td>
      </tr>
      <tr>
        <th>2024-12-17 09:30:00.001483</th>
        <td>ARCA</td>
        <td>604.19</td>
        <td>231.0</td>
      </tr>
      <tr>
        <th>2024-12-17 09:30:00.001564</th>
        <td>NASDAQ</td>
        <td>604.19</td>
        <td>100.0</td>
      </tr>
    </tbody>
  </table>
</div>";

include(DOCS_RESOURCES."/history/ticks.php");
?>
