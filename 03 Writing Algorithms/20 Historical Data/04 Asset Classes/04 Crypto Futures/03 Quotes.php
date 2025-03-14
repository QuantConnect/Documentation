<?
$symbolC = "var symbol = AddCryptoFuture(\"BTCUSD\").Symbol;";
$symbolPy = "symbol = self.add_crypto_future('BTCUSD').symbol";
$assetClass = "CryptoFuture";
$dataTypeLink = "/docs/v2/writing-algorithms/securities/asset-classes/crypto-futures/handling-data#04-Quotes";
$dataType = "QuoteBar";
$supportsQuoteSize = true;
$dataFrame = "<div class='dataframe-wrapper'>
<table class='dataframe python'>
  <thead>
    <tr style='text-align: right;'>
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
      <th rowspan='5' valign='top'>BTCUSD</th>
      <th>2024-12-19 04:56:00</th>
      <td>100744.2</td>
      <td>100753.9</td>
      <td>100727.5</td>
      <td>100753.9</td>
      <td>6420.0</td>
      <td>100744.1</td>
      <td>100753.8</td>
      <td>100727.1</td>
      <td>100753.8</td>
      <td>4237.0</td>
      <td>100744.15</td>
      <td>100753.85</td>
      <td>100727.30</td>
      <td>100753.85</td>
    </tr>
    <tr>
      <th>2024-12-19 04:57:00</th>
      <td>100727.3</td>
      <td>100744.2</td>
      <td>100727.3</td>
      <td>100744.2</td>
      <td>5674.0</td>
      <td>100727.2</td>
      <td>100744.1</td>
      <td>100727.2</td>
      <td>100744.1</td>
      <td>742.0</td>
      <td>100727.25</td>
      <td>100744.15</td>
      <td>100727.25</td>
      <td>100744.15</td>
    </tr>
    <tr>
      <th>2024-12-19 04:58:00</th>
      <td>100698.5</td>
      <td>100727.3</td>
      <td>100653.0</td>
      <td>100727.3</td>
      <td>6707.0</td>
      <td>100698.4</td>
      <td>100727.2</td>
      <td>100652.8</td>
      <td>100727.2</td>
      <td>3719.0</td>
      <td>100698.45</td>
      <td>100727.25</td>
      <td>100652.90</td>
      <td>100727.25</td>
    </tr>
    <tr>
      <th>2024-12-19 04:59:00</th>
      <td>100606.1</td>
      <td>100698.5</td>
      <td>100606.1</td>
      <td>100698.5</td>
      <td>1.0</td>
      <td>100606.0</td>
      <td>100698.4</td>
      <td>100606.0</td>
      <td>100698.4</td>
      <td>5076.0</td>
      <td>100606.05</td>
      <td>100698.45</td>
      <td>100606.05</td>
      <td>100698.45</td>
    </tr>
    <tr>
      <th>2024-12-19 05:00:00</th>
      <td>100644.1</td>
      <td>100655.4</td>
      <td>100606.1</td>
      <td>100606.1</td>
      <td>6005.0</td>
      <td>100644.0</td>
      <td>100655.3</td>
      <td>100606.0</td>
      <td>100606.0</td>
      <td>611.0</td>
      <td>100644.05</td>
      <td>100655.35</td>
      <td>100606.05</td>
      <td>100606.05</td>
    </tr>
  </tbody>
</table>
</div>";

$series = "symbol  time               
BTCUSD  2024-12-19 04:56:00    0.1
        2024-12-19 04:57:00    0.1
        2024-12-19 04:58:00    0.1
        2024-12-19 04:59:00    0.1
        2024-12-19 05:00:00    0.1
dtype: float64";

include(DOCS_RESOURCES."/history/quotebars.php");
?>
