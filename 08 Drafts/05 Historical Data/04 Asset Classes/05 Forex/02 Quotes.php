<?
$symbolC = "AddForex(\"EURUSD\").Symbol";
$symbolPy = "self.add_forex('EURUSD').symbol";
$assetClass = "Forex";
$dataTypeLink = "/docs/v2/writing-algorithms/securities/asset-classes/forex/handling-data#02-Quotes";
$dataType = "QuoteBar";
$supportsQuoteSize = false;
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
      <th>bidclose</th>
      <th>bidhigh</th>
      <th>bidlow</th>
      <th>bidopen</th>
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
    </tr>
  </thead>
  <tbody>
    <tr>
      <th rowspan='5' valign='top'>EURUSD</th>
      <th>2024-12-18 23:56:00</th>
      <td>1.03782</td>
      <td>1.03809</td>
      <td>1.03779</td>
      <td>1.03809</td>
      <td>1.03766</td>
      <td>1.03794</td>
      <td>1.03764</td>
      <td>1.03794</td>
      <td>1.037740</td>
      <td>1.038015</td>
      <td>1.037715</td>
      <td>1.038015</td>
    </tr>
    <tr>
      <th>2024-12-18 23:57:00</th>
      <td>1.03772</td>
      <td>1.03789</td>
      <td>1.03769</td>
      <td>1.03782</td>
      <td>1.03757</td>
      <td>1.03773</td>
      <td>1.03755</td>
      <td>1.03766</td>
      <td>1.037645</td>
      <td>1.037810</td>
      <td>1.037620</td>
      <td>1.037740</td>
    </tr>
    <tr>
      <th>2024-12-18 23:58:00</th>
      <td>1.03784</td>
      <td>1.03786</td>
      <td>1.03769</td>
      <td>1.03772</td>
      <td>1.03768</td>
      <td>1.03771</td>
      <td>1.03754</td>
      <td>1.03757</td>
      <td>1.037760</td>
      <td>1.037785</td>
      <td>1.037615</td>
      <td>1.037645</td>
    </tr>
    <tr>
      <th>2024-12-18 23:59:00</th>
      <td>1.03801</td>
      <td>1.03807</td>
      <td>1.03784</td>
      <td>1.03784</td>
      <td>1.03784</td>
      <td>1.03790</td>
      <td>1.03768</td>
      <td>1.03768</td>
      <td>1.037925</td>
      <td>1.037985</td>
      <td>1.037760</td>
      <td>1.037760</td>
    </tr>
    <tr>
      <th>2024-12-19 00:00:00</th>
      <td>1.03798</td>
      <td>1.03803</td>
      <td>1.03795</td>
      <td>1.03801</td>
      <td>1.03782</td>
      <td>1.03788</td>
      <td>1.03779</td>
      <td>1.03784</td>
      <td>1.037900</td>
      <td>1.037955</td>
      <td>1.037870</td>
      <td>1.037925</td>
    </tr>
  </tbody>
</table>
</div>";

$series = "symbol  time               
EURUSD  2024-12-18 23:56:00    0.00016
        2024-12-18 23:57:00    0.00015
        2024-12-18 23:58:00    0.00016
        2024-12-18 23:59:00    0.00017
        2024-12-19 00:00:00    0.00016
dtype: float64";

include(DOCS_RESOURCES."/history/quotebars.php");
?>
