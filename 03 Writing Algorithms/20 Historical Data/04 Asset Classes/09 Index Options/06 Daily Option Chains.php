<?
$optionC = "AddIndexOption(\"SPX\")";
$optionPy = "self.add_index_option('SPX')";
$underlyingAssetClass = "Index";
$dataTypeLink = "/docs/v2/writing-algorithms/universes/index-options#08-Navigate-Daily-Option-Chains";
$dataFrame = "<div class='dataframe-wrapper'>
<table class='dataframe python'>
  <thead>
    <tr style='text-align: right;'>
      <th></th>
      <th></th>
      <th>close</th>
      <th>delta</th>
      <th>gamma</th>
      <th>high</th>
      <th>impliedvolatility</th>
      <th>low</th>
      <th>open</th>
      <th>openinterest</th>
      <th>rho</th>
      <th>theta</th>
      <th>underlying</th>
      <th>value</th>
      <th>vega</th>
      <th>volume</th>
    </tr>
    <tr>
      <th>time</th>
      <th>symbol</th>
      <th></th>
      <th></th>
      <th></th>
      <th></th>
      <th></th>
      <th></th>
      <th></th>
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
      <th rowspan='5' valign='top'>2024-12-12</th>
      <th>SPX YOGZ798QKLRI|SPX 31</th>
      <td>5879.60</td>
      <td>1.000000</td>
      <td>0.000000</td>
      <td>5897.45</td>
      <td>0.000000</td>
      <td>5865.00</td>
      <td>5866.45</td>
      <td>5280.0</td>
      <td>0.051183</td>
      <td>-0.030094</td>
      <td>SPX: ¤6,084.20</td>
      <td>5879.60</td>
      <td>0.000000</td>
      <td>9.0</td>
    </tr>
    <tr>
      <th>SPX YP8JPVHGPQ9A|SPX 31</th>
      <td>5878.95</td>
      <td>1.000000</td>
      <td>0.000000</td>
      <td>5898.45</td>
      <td>0.000000</td>
      <td>5864.30</td>
      <td>5866.55</td>
      <td>68.0</td>
      <td>0.203532</td>
      <td>-0.029968</td>
      <td>SPX: ¤6,084.20</td>
      <td>5878.95</td>
      <td>0.000000</td>
      <td>0.0</td>
    </tr>
    <tr>
      <th>SPX YQ70D5ADE4VI|SPX 31</th>
      <td>5876.70</td>
      <td>1.000000</td>
      <td>0.000000</td>
      <td>5892.70</td>
      <td>0.000000</td>
      <td>5862.20</td>
      <td>5863.70</td>
      <td>42.0</td>
      <td>0.392162</td>
      <td>-0.029810</td>
      <td>SPX: ¤6,084.20</td>
      <td>5876.70</td>
      <td>0.000000</td>
      <td>0.0</td>
    </tr>
    <tr>
      <th>SPX YQYKVRJ3J9DA|SPX 31</th>
      <td>5873.70</td>
      <td>1.000000</td>
      <td>0.000000</td>
      <td>5889.75</td>
      <td>0.000000</td>
      <td>5856.85</td>
      <td>5860.50</td>
      <td>35.0</td>
      <td>0.541633</td>
      <td>-0.029685</td>
      <td>SPX: ¤6,084.20</td>
      <td>5873.70</td>
      <td>0.000000</td>
      <td>0.0</td>
    </tr>
    <tr>
      <th>SPX YRP5YAENLMPA|SPX 31</th>
      <td>5873.15</td>
      <td>0.999970</td>
      <td>0.000000</td>
      <td>5888.45</td>
      <td>1.651861</td>
      <td>5857.20</td>
      <td>5859.35</td>
      <td>1.0</td>
      <td>0.680416</td>
      <td>-0.032476</td>
      <td>SPX: ¤6,084.20</td>
      <td>5873.15</td>
      <td>0.004516</td>
      <td>0.0</td>
    </tr>
    <tr>
      <th>...</th>
      <th>...</th>
      <td>...</td>
      <td>...</td>
      <td>...</td>
      <td>...</td>
      <td>...</td>
      <td>...</td>
      <td>...</td>
      <td>...</td>
      <td>...</td>
      <td>...</td>
      <td>...</td>
      <td>...</td>
      <td>...</td>
      <td>...</td>
    </tr>
    <tr>
      <th rowspan='5' valign='top'>2024-12-18</th>
      <th>SPX 32RLQ2G18QHCE|SPX 31</th>
      <td>4740.75</td>
      <td>-0.999435</td>
      <td>0.000002</td>
      <td>4751.20</td>
      <td>0.268745</td>
      <td>4727.55</td>
      <td>4738.45</td>
      <td>0.0</td>
      <td>-44.100768</td>
      <td>1.613144</td>
      <td>SPX: ¤6,050.31</td>
      <td>4740.75</td>
      <td>0.077120</td>
      <td>0.0</td>
    </tr>
    <tr>
      <th>SPX 32XJE2QF1AJ7Y|SPX 31</th>
      <td>5445.85</td>
      <td>-0.675418</td>
      <td>0.000079</td>
      <td>5466.35</td>
      <td>0.753369</td>
      <td>5424.20</td>
      <td>5445.05</td>
      <td>28.0</td>
      <td>-101.357524</td>
      <td>-0.721391</td>
      <td>SPX: ¤6,050.31</td>
      <td>5445.85</td>
      <td>21.834520</td>
      <td>0.0</td>
    </tr>
    <tr>
      <th>SPX 337HSSRKH55N2|SPX 31</th>
      <td>5001.30</td>
      <td>-0.997608</td>
      <td>0.000006</td>
      <td>5012.40</td>
      <td>0.139114</td>
      <td>4990.20</td>
      <td>4999.30</td>
      <td>3.0</td>
      <td>-214.914871</td>
      <td>1.611553</td>
      <td>SPX: ¤6,050.31</td>
      <td>5001.30</td>
      <td>0.638336</td>
      <td>0.0</td>
    </tr>
    <tr>
      <th>SPX 33HG7ISPWZS26|SPX 31</th>
      <td>4583.80</td>
      <td>-0.992253</td>
      <td>0.000017</td>
      <td>4595.60</td>
      <td>0.118943</td>
      <td>4574.20</td>
      <td>4582.00</td>
      <td>10.0</td>
      <td>-303.860365</td>
      <td>1.514502</td>
      <td>SPX: ¤6,050.31</td>
      <td>4583.80</td>
      <td>2.232716</td>
      <td>0.0</td>
    </tr>
    <tr>
      <th>SPX 33REM8TVCUEHA|SPX 31</th>
      <td>4193.20</td>
      <td>-0.975511</td>
      <td>0.000043</td>
      <td>4205.90</td>
      <td>0.111797</td>
      <td>4185.60</td>
      <td>4191.90</td>
      <td>8.0</td>
      <td>-379.486892</td>
      <td>1.404206</td>
      <td>SPX: ¤6,050.31</td>
      <td>4193.20</td>
      <td>6.947694</td>
      <td>0.0</td>
    </tr>
  </tbody>
</table>
</div>";

$series = "time        symbol                  
2024-12-12  SPX YOGZ79IHUCOE|SPX 31     20200.0
            SPX 32NKZCPHP4626|SPX 31    18417.0
2024-12-13  SPX YOGZ79IHUCOE|SPX 31      7109.0
            SPX 32OCJVBQ3CMGE|SPX 31     5930.0
2024-12-14  SPX YOGZ79IHUCOE|SPX 31     17443.0
            SPX 32NKZCRZNW3RI|SPX 31    16458.0
2024-12-17  SPX YOGZ7C245MVI|SPX 31     32796.0
            SPX 32NKZCS1BFG9A|SPX 31    30285.0
2024-12-18  SPX YOGZ79IHUCOE|SPX 31     14183.0
            SPX YOGZ7C245MVI|SPX 31     13335.0
Name: volume, dtype: float64";

include(DOCS_RESOURCES."/history/daily_option_chains.php");
?>
