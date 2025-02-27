<?
$symbolC = "AddCrypto(\"BTCUSD\", market: Market.Bitfinex).Symbol";
$symbolPy = "self.add_crypto('BTCUSD', market=Market.BITFINEX).symbol";
$assetClass = "Crypto";
$dataTypeLink = "/docs/v2/writing-algorithms/securities/asset-classes/crypto/handling-data#04-Quotes";
$dataType = "QuoteBar";
$dataFrame = "<div class='dataframe-wrapper'>
<table class="dataframe python">
  <thead>
    <tr style="text-align: right;">
      <th></th>
      <th></th>
      <th>askclose</th>
      <th>askhigh</th>
      <th>asklow</th>
      <th>askopen</th>
      <th>asksize</th>
      <th>bidclose</th>
      <th>bidhigh</th>
      <th>bidlow</th>
      <th>bidopen</th>
      <th>bidsize</th>
      <th>close</th>
      <th>high</th>
      <th>low</th>
      <th>open</th>
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
      <th rowspan="5" valign="top">BTCUSD</th>
      <th>2024-12-19 04:56:00</th>
      <td>100830.0</td>
      <td>100830.0</td>
      <td>100830.0</td>
      <td>100830.0</td>
      <td>0.496728</td>
      <td>100820.0</td>
      <td>100820.0</td>
      <td>100820.0</td>
      <td>100820.0</td>
      <td>0.050586</td>
      <td>100825.0</td>
      <td>100825.0</td>
      <td>100825.0</td>
      <td>100825.0</td>
    </tr>
    <tr>
      <th>2024-12-19 04:57:00</th>
      <td>100810.0</td>
      <td>100830.0</td>
      <td>100810.0</td>
      <td>100830.0</td>
      <td>1.200384</td>
      <td>100800.0</td>
      <td>100820.0</td>
      <td>100800.0</td>
      <td>100820.0</td>
      <td>0.130504</td>
      <td>100805.0</td>
      <td>100825.0</td>
      <td>100805.0</td>
      <td>100825.0</td>
    </tr>
    <tr>
      <th>2024-12-19 04:58:00</th>
      <td>100760.0</td>
      <td>100810.0</td>
      <td>100760.0</td>
      <td>100810.0</td>
      <td>0.520363</td>
      <td>100750.0</td>
      <td>100800.0</td>
      <td>100750.0</td>
      <td>100800.0</td>
      <td>0.275894</td>
      <td>100755.0</td>
      <td>100805.0</td>
      <td>100755.0</td>
      <td>100805.0</td>
    </tr>
    <tr>
      <th>2024-12-19 04:59:00</th>
      <td>100710.0</td>
      <td>100760.0</td>
      <td>100710.0</td>
      <td>100760.0</td>
      <td>1.716247</td>
      <td>100700.0</td>
      <td>100750.0</td>
      <td>100700.0</td>
      <td>100750.0</td>
      <td>0.270080</td>
      <td>100705.0</td>
      <td>100755.0</td>
      <td>100705.0</td>
      <td>100755.0</td>
    </tr>
    <tr>
      <th>2024-12-19 05:00:00</th>
      <td>100710.0</td>
      <td>100710.0</td>
      <td>100710.0</td>
      <td>100710.0</td>
      <td>0.712784</td>
      <td>100700.0</td>
      <td>100700.0</td>
      <td>100700.0</td>
      <td>100700.0</td>
      <td>0.389266</td>
      <td>100705.0</td>
      <td>100705.0</td>
      <td>100705.0</td>
      <td>100705.0</td>
    </tr>
  </tbody>
</table>
</div>";

$series = "symbol  time               
BTCUSD  2024-12-19 04:56:00    10.0
        2024-12-19 04:57:00    10.0
        2024-12-19 04:58:00    10.0
        2024-12-19 04:59:00    10.0
        2024-12-19 05:00:00    10.0
dtype: float64";

include(DOCS_RESOURCES."/history/quotebars.php");
?>
