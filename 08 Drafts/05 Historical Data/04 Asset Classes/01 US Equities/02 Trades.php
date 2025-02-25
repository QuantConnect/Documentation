<?
$symbolC = "AddEquity(\"SPY\").Symbol";
$symbolPy = "self.add_equity('SPY').symbol";
$assetClass = "USEquity";
$dataTypeLink = "/docs/v2/writing-algorithms/securities/asset-classes/us-equity/handling-data#03-Trades";
$dataType = "TradeBar";
$dataFrame = "<div class='dataframe-wrapper'><table class='dataframe python'>
  <thead>
    <tr style='text-align: right;'>
      <th></th>
      <th></th>
      <th>close</th>
      <th>high</th>
      <th>low</th>
      <th>open</th>
      <th>volume</th>
    </tr>
    <tr>
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
      <th rowspan='5' valign='top'>SPY</th>
      <th>2024-12-12 16:00:00</th>
      <td>604.33</td>
      <td>607.160</td>
      <td>604.33</td>
      <td>606.64</td>
      <td>24962360.0</td>
    </tr>
    <tr>
      <th>2024-12-13 16:00:00</th>
      <td>604.21</td>
      <td>607.100</td>
      <td>602.82</td>
      <td>606.38</td>
      <td>29250856.0</td>
    </tr>
    <tr>
      <th>2024-12-16 16:00:00</th>
      <td>606.79</td>
      <td>607.775</td>
      <td>605.22</td>
      <td>606.00</td>
      <td>33686372.0</td>
    </tr>
    <tr>
      <th>2024-12-17 16:00:00</th>
      <td>604.29</td>
      <td>605.160</td>
      <td>602.89</td>
      <td>604.22</td>
      <td>38527534.0</td>
    </tr>
    <tr>
      <th>2024-12-18 16:00:00</th>
      <td>586.28</td>
      <td>606.400</td>
      <td>585.89</td>
      <td>604.00</td>
      <td>80642184.0</td>
    </tr>
  </tbody>
</table></div>";

$series = "symbol  time               
SPY     2024-12-13 16:00:00   -0.000199
        2024-12-16 16:00:00    0.004270
        2024-12-17 16:00:00   -0.004120
        2024-12-18 16:00:00   -0.029804
Name: close, dtype: float64";

include(DOCS_RESOURCES."/history/tradebars.php");
?>
