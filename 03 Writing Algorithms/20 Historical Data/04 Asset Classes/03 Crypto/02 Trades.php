<?
$symbolC = "var symbol = AddCrypto(\"BTCUSD\").Symbol;";
$symbolPy = "symbol = self.add_crypto('BTCUSD').symbol";
$assetClass = "Crypto";
$dataTypeLink = "/docs/v2/writing-algorithms/securities/asset-classes/crypto/handling-data#03-Trades";
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
      <th rowspan='5' valign='top'>BTCUSD</th>
      <th>2024-12-15</th>
      <td>101399.99</td>
      <td>102650.00</td>
      <td>100600.00</td>
      <td>101423.26</td>
      <td>4054.541500</td>
    </tr>
    <tr>
      <th>2024-12-16</th>
      <td>104439.88</td>
      <td>105100.00</td>
      <td>101221.34</td>
      <td>101400.00</td>
      <td>7216.743790</td>
    </tr>
    <tr>
      <th>2024-12-17</th>
      <td>106099.81</td>
      <td>107857.79</td>
      <td>103289.21</td>
      <td>104445.15</td>
      <td>22263.157625</td>
    </tr>
    <tr>
      <th>2024-12-18</th>
      <td>106150.00</td>
      <td>108388.88</td>
      <td>105337.97</td>
      <td>106099.98</td>
      <td>11729.293641</td>
    </tr>
    <tr>
      <th>2024-12-19</th>
      <td>100150.73</td>
      <td>106528.13</td>
      <td>99939.82</td>
      <td>106147.77</td>
      <td>21659.470502</td>
    </tr>
  </tbody>
</table></div>";

$series = "symbol  time      
BTCUSD  2024-12-16    0.029979
        2024-12-17    0.015894
        2024-12-18    0.000473
        2024-12-19   -0.056517
Name: close, dtype: float64";

include(DOCS_RESOURCES."/history/tradebars.php");
?>
